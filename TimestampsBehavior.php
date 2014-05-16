<?php
namespace Asgard\Behaviors;

class TimestampsBehavior extends \Asgard\Entity\Behavior {
	public function load(\Asgard\Entity\EntityDefinition $definition) {
		$definition->addProperty('created_at', array('type' => 'datetime', 'required' => false, 'editable' => false, 'default' => function() { return \Carbon\Carbon::now(); }));
		$definition->addProperty('updated_at', array('type' => 'datetime', 'required' => false, 'editable' => false, 'default' => function() { return \Carbon\Carbon::now(); }));

		$definition->hook('save', function($chain, \Asgard\Entity\Entity $entity) {
			$entity->updated_at = \Carbon\Carbon::now();
		});
	}
}