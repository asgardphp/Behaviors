<?php
namespace Asgard\Behaviors;

class TimestampsBehavior implements \Asgard\Core\Behavior {
	public static function load($entityDefinition, $params=null) {
		$entityDefinition->addProperty('created_at', array('type' => 'datetime', 'required' => false, 'editable' => false, 'default' => function() { return new \Asgard\Utils\Datetime(); }));
		$entityDefinition->addProperty('updated_at', array('type' => 'datetime', 'required' => false, 'editable' => false, 'default' => function() { return new \Asgard\Utils\Datetime(); }));

		$entityDefinition->hook('set', function($chain, $entity, $name, $value, $lang) {
			if($name == 'updated_at' || is_array($name) && in_array('updated_at', array_keys($name)))
				return;
			$entity->updated_at = new \Asgard\Utils\Datetime();
		});
	}
}