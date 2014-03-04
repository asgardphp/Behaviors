<?php
namespace Asgard\Behaviors\Controllers;

class PublishBehaviorController extends \Asgard\Core\Controller {
	public function publishAction($request) {
		$controller = $request->parentController.'Controller';
		$entityName = $controller::getEntity();
		$entity = $entityName::load($request['id']);
		$entity->save(array('published'=>!$entity->published));
		return \Asgard\Core\App::get('response')->back();
	}
}