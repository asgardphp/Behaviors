<?php
namespace Coxis\Behaviors;

class PageBehavior implements \Coxis\Core\Behavior {
	public static function load($entityDefinition, $params=null) {
		if(isset($entityDefinition->behaviors['page'])) {
			$entityDefinition->behaviors['metas'] = true;
			$entityDefinition->behaviors['slugify'] = true;
			unset($entityDefinition->behaviors['page']);
		}
	}
}