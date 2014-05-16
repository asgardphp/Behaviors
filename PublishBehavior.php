<?php
namespace Asgard\Behaviors;

class PublishBehavior extends \Asgard\Entity\Behavior {
	protected $entityClass;

	public function load(\Asgard\Entity\EntityDefinition $definition) {
		$definition->addProperty('published', array('type'=>'boolean', 'default'=>true));
		$this->entityClass = $definition->getClass();
	}

	#Article::published()
	public function static_published() {
		$entityClass = $this->entityClass;

		return $entityClass::orm()->where(array('published'=>1));
	}

	#Article::loadPublished(2)
	public function static_loadPublished($id) {
		return static::static_published()->where(array('id'=>$id))->first();
	}
}