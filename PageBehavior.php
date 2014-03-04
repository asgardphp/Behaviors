<?php
namespace Asgard\Behaviors;

class PageBehavior implements \Asgard\Core\Behavior {
	public static function load($entityDefinition, $params=null) {
		if(!isset($entityDefinition->behaviors['Asgard\Behaviors\MetasBehavior']))
			$entityDefinition->behaviors['Asgard\Behaviors\MetasBehavior'] = true;
		if(!isset($entityDefinition->behaviors['Asgard\Behaviors\SlugifyBehavior']))
			$entityDefinition->behaviors['Asgard\Behaviors\SlugifyBehavior'] = true;
	}
}