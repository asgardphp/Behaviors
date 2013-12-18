<?php
namespace Coxis\Behaviors;

class TimestampsBehavior implements \Coxis\Core\Behavior {
	public static function load($entityDefinition, $params=null) {
		$entityDefinition->addProperty('created_at', array('type' => 'datetime', 'required' => false, 'editable' => false));
		$entityDefinition->addProperty('updated_at', array('type' => 'datetime', 'required' => false, 'editable' => false));

		$entityDefinition->hook('behaviors_presave', function($chain, $entity) {
			if(!$entity->created_at)
				$entity->created_at = new \Coxis\Utils\Datetime(time());
			$entity->updated_at = new \Coxis\Utils\Datetime(time());
		});
	}
}