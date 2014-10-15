<?php
namespace Asgard\Behaviors;

class SlugifyBehavior extends \Asgard\Entity\Behavior {
	public function load(\Asgard\Entity\Definition $definition) {
		$definition->addProperty('slug', array('type' => 'text', 'required' => false));
	}

	public function call_slug(\Asgard\Entity\Entity $entity) {
		$slug_from = $this->params;

		if($entity->slug)
			return $entity->slug;
		if($slug_from !== null && $entity->hasProperty($slug_from))
			return static::slugify($entity->get($slug_from));
		elseif(method_exists($entity, '__toString'))
			return static::slugify($entity->__toString());
	}

	protected static function slugify($text) {
		$text = \Asgard\Common\Tools::removeAccents($text);

		#replace non letter or digits by -
		$text = preg_replace('~[^\\pL\d]+~u', '-', $text);

		#trim
		$text = trim($text, '-');

		#transliterate
		if (function_exists('iconv'))
			$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

		#lowercase
		$text = strtolower($text);

		#remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);

		if (empty($text))
			return 'n-a';

		return $text;
	}
}