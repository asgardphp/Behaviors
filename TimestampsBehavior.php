<?php
namespace Asgard\Behaviors;

class TimestampsBehavior extends \Asgard\Entity\Behavior {
	public function load(\Asgard\Entity\Definition $definition) {
		$definition->addProperty('created_at', array('type' => 'datetime', 'required' => false, 'editable' => false, 'default' => function() { return \Asgard\Common\Datetime::now(); }));
		$definition->addProperty('updated_at', array('type' => 'datetime', 'required' => false, 'editable' => false, 'default' => function() { return \Asgard\Common\Datetime::now(); }));

		$definition->hook('save', function($chain, \Asgard\Entity\Entity $entity) {
			$entity->updated_at = \Asgard\Common\Datetime::now();
		});
	}
}