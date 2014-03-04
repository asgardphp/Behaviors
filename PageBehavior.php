<?php
namespace Coxis\Behaviors;

class PageBehavior implements \Coxis\Core\Behavior {
	public static function load($entityDefinition, $params=null) {
		if(!isset($entityDefinition->behaviors['Coxis\Behaviors\MetasBehavior']))
			$entityDefinition->behaviors['Coxis\Behaviors\MetasBehavior'] = true;
		if(!isset($entityDefinition->behaviors['Coxis\Behaviors\SlugifyBehavior']))
			$entityDefinition->behaviors['Coxis\Behaviors\SlugifyBehavior'] = true;
	}
}