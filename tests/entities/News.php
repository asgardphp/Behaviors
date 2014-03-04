<?php
namespace Coxis\Behaviors\Tests\Entities;

class News extends \Coxis\Core\Entity {
	public static $properties = array(
		'title',
		'content',
	);

	public static $behaviors = array(
		'Coxis\Behaviors\PageBehavior',
		'Coxis\Behaviors\SlugifyBehavior' => 'title',
		'Coxis\Behaviors\PublishBehavior',
	);

	public function __toString() {
		return $this->title;
	}
}