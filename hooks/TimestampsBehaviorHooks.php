<?php
namespace Coxis\Behaviors\Hooks;

class TimestampsBehaviorHooks extends \Coxis\Hook\HooksContainer {
	/**
	@Hook('behaviors_pre_load')
	**/
	public static function behaviors_pre_load($chain, $entityDefinition) {
		if(!isset($entityDefinition->behaviors['Coxis\Behaviors\TimestampsBehavior']))
			$entityDefinition->behaviors['Coxis\Behaviors\TimestampsBehavior'] = true;
	}
}