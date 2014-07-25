<?php
namespace Asgard\Behaviors\Hooks;

class SortableHooks extends \Asgard\Hook\HooksContainer {
	/**
	 * @Hook("asgard_actions")
	 */
	public static function asgardActions($chain, $entity) {
		if($entity->getDefinition()->hasBehavior('Asgard\Behaviors\SortableBehavior')) {
			$alias = $chain->container->get('adminManager')->getAlias(get_class($entity));
			echo '<a href="'.$chain->container->get('resolver')->url_for(array('Asgard\Behaviors\Controllers\SortableController', 'promote'), array('entityAlias'=>$alias, 'id' => $entity->id)).'">'.__('Promote').'</a> | <a href="'.$chain->container->get('resolver')->url_for(array('Asgard\Behaviors\Controllers\SortableController', 'demote'), array('entityAlias'=>$alias, 'id' => $entity->id)).'">'.__('Demote').'</a> | ';
		}
	}
}