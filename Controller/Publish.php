<?php
namespace Asgard\Behaviors\Controller;

/**
 * @Prefix("admin/sortable/:entityAlias/:id")
 */
class Publish extends \Admin\Libs\Controller\AdminParentController {
	public function before(\Asgard\Http\Request $request) {
		$this->layout = false;
		$entityAlias = $request['entityAlias'];
		$this->entityClass = $entityClass = \Asgard\Container\Container::get('adminManager')->getClass($entityAlias);
		if(!($this->entity = $entityClass::load($request['id'])))
			$this->forward404();

		return parent::before($request);
	}

	/**
	 * @Route("publish")
	 */
	public function publishAction(\Asgard\Http\Request $request) {
		$this->entity->save(['published'=>!$this->entity->published]);
		if($this->entity->published)
			$this->getFlash()->addSuccess($this->container['translator']->trans('Element published with success.'));
		else
			$this->getFlash()->addSuccess($this->container['translator']->trans('Element unpublished with success.'));
		return $this->response->back();
	}
}