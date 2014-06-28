<?php
namespace Asgard\Behaviors;

class Bundle extends \Asgard\Core\BundleLoader {
	protected function loadControllers() {
		if(!class_exists('Admin\Libs\Controller\AdminParentController'))
			return [];
		return parent::loadControllers();
	}
}