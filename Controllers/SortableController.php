<?php
namespace Asgard\Behaviors\Controllers;

/**
 * @Prefix("admin/sortable/:entityAlias/:id")
 */
class SortableController extends \Admin\Libs\Controller\AdminParentController {
	public function before(\Asgard\Http\Request $request) {
		$this->layout = false;
		$entityAlias = $request['entityAlias'];
		$this->entityClass = $entityClass = \Asgard\Container\Container::get('adminManager')->getClass($entityAlias);
		if(!($this->entity = $entityClass::load($request['id'])))
			$this->forward404();

		return parent::before($request);
	}

	/**
	 * @Route("promote")
	 */
	public function promoteAction(\Asgard\Http\Request $request) {
		$entityClass = $this->entityClass;
		$entity = $this->entity;

		static::reset($entityClass);

		try {
			$separate_by = $entity->getDefinition()->separate_by;
			if($separate_by)
				$over_entity = $entityClass::where(['position < ?'=>$entity->position, $separate_by=>$entity->$separate_by])->orderBy('position DESC')->first();
			else
				$over_entity = $entityClass::where(['position < ?'=>$entity->position])->orderBy('position DESC')->first();

			$old = $entity->position;
			$entity->position = $over_entity->position;
			$over_entity->position = $old;
			$entity->save(null, true);
			$over_entity->save(null, true);
			$this->getFlash()->addSuccess($this->container['translator']->trans('Order modified with success.'));
		} catch(\Exception $e) {d($e);}

		return $this->response->back();
	}

	/**
	 * @Route("demote")
	 */
	public function demoteAction(\Asgard\Http\Request $request) {
		$entityClass = $this->entityClass;
		$entity = $this->entity;
		static::reset($entityClass);

		try {
			$separate_by = $entity->getDefinition()->separate_by;
			if($separate_by)
				$below_entity = $entityClass::where(['position > ?'=>$entity->position, $separate_by=>$entity->$separate_by])->orderBy('position ASC')->first();
			else
				$below_entity = $entityClass::where(['position > ?'=>$entity->position])->orderBy('position ASC')->first();

			$old = $entity->position;
			$entity->position = $below_entity->position;
			$below_entity->position = $old;
			$entity->save(null, true);
			$below_entity->save(null, true);
			$this->getFlash()->addSuccess($this->container['translator']->trans('Order modified with success.'));
		} catch(\Exception $e) {}

		return $this->response->back();
	}

	protected static function reset($entityClass) {
		$all = $entityClass::orderBy('position ASC')->get();

		#reset positions
		foreach($all as $i=>$one_entity) {
			$one_entity->position = $i;
			$one_entity->save(null, true);
		}
	}
}