<?php
namespace Asgard\Behaviors\Tests\Entities;

class News extends \Asgard\Core\Entity {
	public static $properties = array(
		'title',
		'content',
	);

	public static $behaviors = array(
		'Asgard\Behaviors\PageBehavior',
		'Asgard\Behaviors\SlugifyBehavior' => 'title',
		'Asgard\Behaviors\PublishBehavior',
	);

	public function __toString() {
		return $this->title;
	}
}