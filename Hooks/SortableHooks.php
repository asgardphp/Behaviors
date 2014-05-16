<?php
namespace Asgard\Behaviors\Hooks;

class SortableHooks extends \Asgard\Hook\HooksContainer {
	/**
	@Hook('asgard_actions')
	**/
	public static function asgardActions($chain, $entity) {
		if($entity->getDefinition()->hasBehavior('Asgard\Behaviors\SortableBehavior')) {
			$alias = \Asgard\Core\App::get('adminManager')->getAlias(get_class($entity));
			return '<a href="'.\Asgard\Core\App::get('url')->url_for(array('Asgard\Behaviors\Controllers\SortableController', 'promote'), array('entityAlias'=>$alias, 'id' => $entity->id)).'">'.__('Promote').'</a> | <a href="'.\Asgard\Core\App::get('url')->url_for(array('Asgard\Behaviors\Controllers\SortableController', 'demote'), array('entityAlias'=>$alias, 'id' => $entity->id)).'">'.__('Demote').'</a> | ';
		}
	}
}