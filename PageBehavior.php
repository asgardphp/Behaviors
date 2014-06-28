<?php
namespace Asgard\Behaviors;

class PageBehavior extends \Asgard\Entity\Behavior {
	public function load(\Asgard\Entity\EntityDefinition $definition) {
		$definition->loadBehavior(new \Asgard\Behaviors\MetasBehavior);
		$definition->loadBehavior(new \Asgard\Behaviors\SlugifyBehavior);
	}
}