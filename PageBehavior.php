<?php
namespace Asgard\Behaviors;

class PageBehavior extends \Asgard\Entity\Behavior {
	public function load(\Asgard\Entity\EntityDefinition $definition) {
		$definition->addBehavior(new \Asgard\Behaviors\MetasBehavior);
		$definition->addBehavior(new \Asgard\Behaviors\SlugifyBehavior);
	}
}