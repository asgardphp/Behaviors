<?php
namespace Asgard\Behaviors\Hooks;

class TimestampsBehaviorHooks extends \Asgard\Hook\HooksContainer {
	/**
	@Hook('behaviors_pre_load')
	**/
	public static function behaviors_pre_load($chain, $entityDefinition) {
		if(!isset($entityDefinition->behaviors['Asgard\Behaviors\TimestampsBehavior']))
			$entityDefinition->behaviors['Asgard\Behaviors\TimestampsBehavior'] = true;
	}
}