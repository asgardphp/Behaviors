<?php
namespace Asgard\Behaviors\Hook;

class Publish extends \Asgard\Hook\HookContainer {
	/**
	 * @Hook("asgard_actions")
	 */
	public static function asgardActions(\Asgard\Hook\Chain $chain, \Asgard\Entity\Entity $entity) {
		$translator = $chain->getContainer()['translator'];

		if($entity->getDefinition()->hasBehavior('Asgard\Behaviors\PublishBehavior')) {
			$alias = $chain->getContainer()['adminManager']->getAlias(get_class($entity));
			echo '<a href="'.$chain->getContainer()['resolver']->url(['Asgard\Behaviors\Controllers\PublishController', 'publish'], ['entityAlias'=>$alias, 'id' => $entity->id]).'">'.($entity->published ? $translator->trans('Unpublish'):$translator->trans('Publish')).'</a> | ';
		}
	}

	/**
	 * @Hook("asgardadmin_globalactions")
	 */
	public static function asgardadminGlobalactions(\Asgard\Hook\Chain $chain, \Asgard\Http\Controller $controller, &$actions) {
		$entityClass = $controller->getEntity();
		if(!$entityClass::getStaticDefinition()->hasBehavior('Asgard\Behaviors\PublishBehavior'))
			return;

		$translator = $chain->getContainer()['translator'];

		#publish
		$actions['publish'] = [
			'text'	=>	$translator->trans('Publish'),
			'callback'	=>	function($entityClass, $controller) use($translator) {
				if($controller->request->post->size() > 1) {
					foreach($controller->request->post->get('id') as $id) {
						$entity = $entityClass::load($id);
						if($entity)
							$entity->save(['published'=>1]);
					}

					$controller->getFlash()->addSuccess(sprintf($translator->trans('%s element(s) published with success!'), count($controller->request->post->get('id'))));
				}
			}
		];
		#unpublish
		$actions['unpublish'] = [
			'text'	=>	$translator->trans('Unpublish'),
			'callback'	=>	function($entityClass, $controller) use($translator) {
				if($controller->request->post->size() > 1) {
					foreach($controller->request->post->get('id') as $id) {
						$entity = $entityClass::load($id);
						if($entity)
							$entity->save(['published'=>0]);
					}

					$controller->getFlash()->addSuccess(sprintf($translator->trans('%s element(s) unpublished with success!'), count($controller->request->post->get('id'))));
				}
			}
		];
	}
}