<?php
namespace Asgard\Behaviors\Hooks;

class SortableHooks extends \Asgard\Hook\HookContainer {
	/**
	 * @Hook("asgard_actions")
	 */
	public static function asgardActions($chain, $entity) {
		if($entity->getDefinition()->hasBehavior('Asgard\Behaviors\SortableBehavior')) {
			$alias = $chain->getContainer()['adminManager']->getAlias(get_class($entity));
			echo '<a href="'.$chain->getContainer()['resolver']->url(['Asgard\Behaviors\Controllers\SortableController', 'promote'], ['entityAlias'=>$alias, 'id' => $entity->id]).'">'.__('Promote').'</a> | <a href="'.$chain->getContainer()['resolver']->url(['Asgard\Behaviors\Controllers\SortableController', 'demote'], ['entityAlias'=>$alias, 'id' => $entity->id]).'">'.__('Demote').'</a> | ';
		}
	}
}