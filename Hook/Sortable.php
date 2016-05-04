<?php
namespace Asgard\Behaviors\Hook;

class Sortable extends \Asgard\Hook\HookContainer {
	/**
	 * @Hook("asgard_actions")
	 */
	public static function asgardActions($chain, $entity) {
		if($entity->getDefinition()->hasBehavior('Asgard\Behaviors\SortableBehavior')) {
			$alias = $chain->getContainer()['adminManager']->getAlias(get_class($entity));
			echo '<a href="'.$chain->getContainer()['resolver']->url(['Asgard\Behaviors\Controller\Sortable', 'promote'], ['entityAlias'=>$alias, 'id' => $entity->id]).'">'.__('Promote').'</a> | <a href="'.$chain->getContainer()['resolver']->url(['Asgard\Behaviors\Controller\Sortable', 'demote'], ['entityAlias'=>$alias, 'id' => $entity->id]).'">'.__('Demote').'</a> | ';
		}
	}
}