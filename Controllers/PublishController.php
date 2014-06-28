<?php
namespace Asgard\Behaviors\Controllers;

/**
 * @Prefix("admin/sortable/:entityAlias/:id")
 */
class PublishController extends \Admin\Libs\Controller\AdminParentController {
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
		$this->entity->save(array('published'=>!$this->entity->published));
		if($this->entity->published)
			\Asgard\Container\Container::get('flash')->addSuccess(__('Element published with success.'));
		else
			\Asgard\Container\Container::get('flash')->addSuccess(__('Element unpublished with success.'));
		return $this->response->back();
	}
}