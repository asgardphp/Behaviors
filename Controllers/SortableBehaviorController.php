<?php
namespace Asgard\Behaviors\Controllers;

class SortableBehaviorController extends \Asgard\Core\Controller {
	public function promoteAction($request) {
		$controller = $request->parentController;
		$entityName = $controller::getEntity();
		$entity = $entityName::load($request['id']);

		static::reset($entityName);
		
		try {
			$separate_by = $entity->getDefinition()->meta('separate_by');
			if($separate_by)
				$over_entity = $entityName::where(array('position < ?'=>$entity->position, $separate_by=>$entity->$separate_by))->orderBy('position DESC')->first();
			else
				$over_entity = $entityName::where(array('position < ?'=>$entity->position))->orderBy('position DESC')->first();
			
			$old = $entity->position;
			$entity->position = $over_entity->position;
			$over_entity->position = $old;
			$entity->save(null, true);
			$over_entity->save(null, true);
			\Asgard\Core\App::get('flash')->addSuccess(__('Ordre modifié avec succès.'));
		} catch(\Exception $e) {}

		return $this->response->back();
	}
	
	public function demoteAction($request) {
		$controller = $request->parentController;
		$entityName = $controller::getEntity();
		$entity = $entityName::load($request['id']);
		static::reset($entityName);
		
		try {
			$separate_by = $entity->getDefinition()->meta('separate_by');
			if($separate_by)
				$below_entity = $entityName::where(array('position > ?'=>$entity->position, $separate_by=>$entity->$separate_by))->orderBy('position ASC')->first();
			else
				$below_entity = $entityName::where(array('position > ?'=>$entity->position))->orderBy('position ASC')->first();
			
			$old = $entity->position;
			$entity->position = $below_entity->position;
			$below_entity->position = $old;
			$entity->save(null, true);
			$below_entity->save(null, true);
			\Asgard\Core\App::get('flash')->addSuccess(__('Ordre modifié avec succès.'));
		} catch(\Exception $e) {}
		
		return \Asgard\Core\App::get('response')->back();
	}
	
	public static function reset($entityName) {
		$all = $entityName::orderBy('position ASC')->get();
		
		#reset positions
		foreach($all as $i=>$one_entity) {
			$one_entity->position = $i;
			$one_entity->save(null, true);
		}
	}
}