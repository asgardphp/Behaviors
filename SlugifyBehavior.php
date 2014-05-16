<?php
namespace Asgard\Behaviors;

class SlugifyBehavior extends \Asgard\Entity\Behavior {
	public function load(\Asgard\Entity\EntityDefinition $definition) {
		$definition->addProperty('slug', array('type' => 'text', 'required' => false));
	}

	public function call_slug(\Asgard\Entity\Entity $entity) {
		$slug_from = $this->params;

		if($entity->slug)
			return $entity->slug;
		if($slug_from !== null && $entity->hasProperty($slug_from))
			return \Asgard\Utils\Tools::slugify($entity->get($slug_from));
		elseif(method_exists($entity, '__toString'))
			return \Asgard\Utils\Tools::slugify($entity->__toString());
	}
}