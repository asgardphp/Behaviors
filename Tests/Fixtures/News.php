<?php
namespace Asgard\Behaviors\Tests\Fixtures;

class News extends \Asgard\Entity\Entity {
	public static function definition(\Asgard\Entity\EntityDefinition $definition) {
		$definition->properties = array(
			'title',
			'content'
		);

		$definition->behaviors = array(
			new \Asgard\Behaviors\PageBehavior,
			new \Asgard\Behaviors\SlugifyBehavior('title'),
			new \Asgard\Behaviors\PublishBehavior,
			new \Asgard\Behaviors\SortableBehavior,
			new \Asgard\Behaviors\TimestampsBehavior,
			new \Asgard\Orm\ORMBehavior,
		);
	}

	public function __toString() {
		return $this->title;
	}
}