<?php
namespace Asgard\Behaviors\Hooks;

class PublishHooks extends \Asgard\Hook\HooksContainer {
	/**
	@Hook('asgard_actions')
	**/
	public static function asgardActions(\Asgard\Hook\HookChain $chain, \Asgard\Entity\Entity $entity) {
		if($entity->getDefinition()->hasBehavior('Asgard\Behaviors\PublishBehavior')) {
			$alias = \Asgard\Core\App::get('adminManager')->getAlias(get_class($entity));
			return '<a href="'.\Asgard\Core\App::get('url')->url_for(array('Asgard\Behaviors\Controllers\PublishController', 'publish'), array('entityAlias'=>$alias, 'id' => $entity->id)).'">'.($entity->published ? __('Unpublish'):__('Publish')).'</a> | ';
		}
	}

	/**
	@Hook('asgardadmin_globalactions')
	**/
	public static function asgardadminGlobalactions(\Asgard\Hook\HookChain $chain, \Asgard\Http\Controller $controller, &$actions) {
		$entityClass = $controller->getEntity();
		if(!$entityClass::getDefinition()->hasBehavior('Asgard\Behaviors\PublishBehavior'))
			return;

		#publish
		$actions['publish'] = array(
			'text'	=>	__('Publish'),
			'callback'	=>	function($entityClass, $controller) {
				if($controller->request->post->size() > 1) {
					foreach($controller->request->post->get('id') as $id) {
						$entity = $entityClass::load($id);
						if($entity)
							$entity->save(array('published'=>1));
					}
				
					\Asgard\Core\App::get('flash')->addSuccess(sprintf(__('%s element(s) published with success!'), count($controller->request->post->get('id'))));
				}
			}
		);
		#unpublish
		$actions['unpublish'] = array(
			'text'	=>	__('Unpublish'),
			'callback'	=>	function($entityClass, $controller) {
				if($controller->request->post->size() > 1) {
					foreach($controller->request->post->get('id') as $id) {
						$entity = $entityClass::load($id);
						if($entity)
							$entity->save(array('published'=>0));
					}
				
					\Asgard\Core\App::get('flash')->addSuccess(sprintf(__('%s element(s) unpublished with success!'), count($controller->request->post->get('id'))));
				}
			}
		);
	}
}