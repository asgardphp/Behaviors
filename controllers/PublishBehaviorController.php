<?php
namespace Coxis\Behaviors\Controllers;

class PublishBehaviorController extends \Coxis\Core\Controller {
	public function publishAction($request) {
		$controller = $request->parentController.'Controller';
		$entityName = $controller::getEntity();
		$entity = $entityName::load($request['id']);
		$entity->save(array('published'=>!$entity->published));
		return \Coxis\Core\App::get('response')->back();
	}
}