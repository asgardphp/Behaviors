<?php
namespace Asgard\Behaviors;

class SortableBehavior extends \Asgard\Entity\Behavior {
	protected $category;

	public function __construct($category=null) {
		$this->category = $category;
	}

	public function load(\Asgard\Entity\Definition $definition) {
		$definition->order_by = 'position ASC';
		$definition->addProperty('position', ['type' => 'decimal', 'precision'=>10, 'default'=>0, 'required' => false, 'editable' => false]);

		$definition->hook('save', function($chain, $entity) {
			if($entity->isNew()) {
				$orm = $entity::orderBy('position DESC');
				$f = $orm->first();
				if($f)
					$entity->position = $f->position + 1;
			}
		});
	}

	#article->moveUp()
	public function call_moveUp(\Asgard\Entity\Entity $entity) {
		$previous = $this->call_previous($entity);
		if(!$previous)
			return;
		$previous2 = $this->call_previous($previous);
		if($previous2)
			$pos = ($previous->position + $previous2->position)/2;
		else
			$pos = $previous->position - 1;
		$entity->save(['position'=>$pos]);
	}

	#article->moveDown()
	public function call_moveDown(\Asgard\Entity\Entity $entity) {
		$next = $this->call_next($entity);
		if(!$next)
			return;
		$next2 = $this->call_next($next);
		if($next2)
			$pos = ($next->position + $next2->position)/2;
		else
			$pos = $next->position + 1;
		$entity->save(['position'=>$pos]);
	}

	#$article->moveAfter()
	public function call_moveAfter(\Asgard\Entity\Entity $entity, $after_id) {
		$orm = $entity::orm();
		if($this->category)
			$orm->where($this->category, $entity->get($this->category));

		if($after_id == 0) {
			$min = $orm::min('position');
			$entity->save(['position' => $min-1]);
		}
		else {
			$i = 0;
			foreach($orm as $one) {
				if($one->id === $entity->id)
					continue;

				$one->save(['position' => $i++]);
				if($one->id == $after_id)
					$entity->save(['position' => $i++]);
			}
		}
	}

	#$article->previous()
	public function call_previous(\Asgard\Entity\Entity $entity) {
		$orm = $entity::where(['position < ?' => $entity->position])->orderBy('position DESC');
		if($this->category)
			$orm->where($this->category, $entity->get($this->category));

		return $orm->first();
	}

	#$article->next()
	public function call_next(\Asgard\Entity\Entity $entity) {
		$orm = $entity::where(['position > ?' => $entity->position])->orderBy('position ASC');
		if($this->category)
			$orm->where($this->category, $entity->get($this->category));

		return $orm->first();
	}
}