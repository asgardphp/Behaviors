<?php
namespace Asgard\Behaviors;

class MetasBehavior extends \Asgard\Entity\Behavior {
	public function load(\Asgard\Entity\EntityDefinition $definition) {
		$definition->addProperty('meta_title', array('type' => 'text', 'required' => false));
		$definition->addProperty('meta_description', array('type' => 'text', 'required' => false));
		$definition->addProperty('meta_keywords', array('type' => 'text', 'required' => false));

	}

	public function call_showMetas(\Asgard\Entity\Entity $entity) {
		\Asgard\Core\App::get('html')->setTitle($entity->meta_title!='' ? html_entity_decode($entity->meta_title):html_entity_decode($entity));
		\Asgard\Core\App::get('html')->setKeywords($entity->meta_keywords);
		\Asgard\Core\App::get('html')->setDescription($entity->meta_description);
	}
}