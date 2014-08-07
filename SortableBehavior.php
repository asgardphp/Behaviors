<?php
namespace Asgard\Behaviors;

class SortableBehavior extends \Asgard\Entity\Behavior {
	public function load(\Asgard\Entity\EntityDefinition $definition) {
		$definition->order_by = 'position ASC';
		$definition->addProperty('position', array('type' => 'integer', 'default'=>0, 'required' => false, 'editable' => false));

		$definition->hook('save', function($chain, $entity) {
			if(!isset($entity->position)) {
				try {
					$entity->position = $entity::orderBy('position DESC')->first()->position + 1;
				} catch(\Exception $e) {
					$entity->position = 0;
				}
			}
		});
	}

	#$article->moveAfter()
	public function call_moveAfter(\Asgard\Entity\Entity $entity, $after_id) {
		if($after_id == 0) {
			$min = $entity::min('position');
			$entity->save(array('position' => $min-1));
		}
		else {
			$i = 0;
			foreach($entity::all() as $one) {
				if($one->id === $entity->id)
					continue;

				$one->save(array('position' => $i++));
				if($one->id == $after_id)
					$entity->save(array('position' => $i++));
			}
		}
	}

	#$article->previous()
	public function call_previous(\Asgard\Entity\Entity $entity) {
		if($res = $entity::where(array('position < ?' => $this->position))->orderBy('position DESC')->first())
			return $res;
		return false;
	}

	#$article->next()
	public function call_next(\Asgard\Entity\Entity $entity) {
		if($res = $entity::where(array('position > ?' => $this->position))->orderBy('position ASC')->first())
			return $res;
		return false;
	}
}