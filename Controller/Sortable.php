<?php
namespace Asgard\Behaviors\Controller;

/**
 * @Prefix("admin/sortable/:entityAlias/:id")
 */
class Sortable extends \Admin\Libs\Controller\AdminParentController {
	public function before(\Asgard\Http\Request $request) {
		$datamapper = $this->container['datamapper'];
		$this->set('layout', false);
		$entityAlias = $request['entityAlias'];
		$this->entityClass = $entityClass = $this->container['adminManager']->getClass($entityAlias);
		$entity = $datamapper->load($entityClass, $request['id']);
		if(!($this->entity = $entity))
			$this->forward404();

		return parent::before($request);
	}

	/**
	 * @Route("promote")
	 */
	public function promoteAction(\Asgard\Http\Request $request) {
		$datamapper = $this->container['datamapper'];
		$entityClass = $this->entityClass;
		$entity = $this->entity;

		$this->reset($entityClass);

		try {
			$separate_by = $entity->getDefinition()->getBehavior('Asgard\Behaviors\SortableBehavior')->category;
			$orm = $datamapper->orm($entityClass);
			if($separate_by)
				$over_entity = $orm->where(['position < ?'=>$entity->position, $separate_by=>$entity->$separate_by])->orderBy('position DESC')->first();
			else
				$over_entity = $orm->where(['position < ?'=>$entity->position])->orderBy('position DESC')->first();

			$old = $entity->position;
			$entity->position = $over_entity->position;
			$over_entity->position = $old;
			$datamapper->save($entity, [], []);
			$datamapper->save($over_entity, [], []);
			$this->getFlash()->addSuccess($this->container['translator']->trans('Order modified with success.'));
		} catch(\Exception $e) {}

		return $this->response->back();
	}

	/**
	 * @Route("demote")
	 */
	public function demoteAction(\Asgard\Http\Request $request) {
		$datamapper = $this->container['datamapper'];
		$entityClass = $this->entityClass;
		$entity = $this->entity;
		$this->reset($entityClass);

		try {
			$separate_by = $entity->getDefinition()->getBehavior('Asgard\Behaviors\SortableBehavior')->category;
			$orm = $datamapper->orm($entityClass);
			if($separate_by)
				$below_entity = $orm->where(['position > ?'=>$entity->position, $separate_by=>$entity->$separate_by])->orderBy('position ASC')->first();
			else
				$below_entity = $orm->where(['position > ?'=>$entity->position])->orderBy('position ASC')->first();

			$old = $entity->position;
			$entity->position = $below_entity->position;
			$below_entity->position = $old;
			$datamapper->save($entity, [], []);
			$datamapper->save($below_entity, [], []);
			$this->getFlash()->addSuccess($this->container['translator']->trans('Order modified with success.'));
		} catch(\Exception $e) {}

		return $this->response->back();
	}

	protected function reset($entityClass) {
		$datamapper = $this->container['datamapper'];
		$orm = $datamapper->orm($entityClass);
		$all = $orm->orderBy('position ASC')->get();

		#reset positions
		foreach($all as $i=>$one_entity) {
			$one_entity->position = $i;
			$datamapper->save($one_entity, [], []);
		}
	}
}