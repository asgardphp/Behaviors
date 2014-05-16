<?php
namespace Asgard\Behaviors\Tests\Entities;

class News extends \Asgard\Entity\Entity {
	public static function definition(\Asgard\Entity\EntityDefinition $definition) {
		$definition->properties = array(
			'title',
			'content'
		);

		$definition->behaviors = array(
			new \Asgard\Behaviors\PageBehavior,
			new \Asgard\Behaviors\SlugifyBehavior('title'),
			new \Asgard\Behaviors\PublishBehavior
		);
	}

	public function __toString() {
		return $this->title;
	}
}