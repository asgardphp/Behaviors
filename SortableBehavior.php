<?php
namespace Asgard\Behaviors;

class SortableBehavior implements \Asgard\Core\Behavior {
	public static function load($entityDefinition, $params=null) {
		$entityDefinition->meta['order_by'] = 'position ASC';
		$entityDefinition->addProperty('position', array('type' => 'integer', 'default'=>0, 'required' => false, 'editable' => false));

		#$article->moveAfter()
		$entityDefinition->addMethod('moveAfter', function($entity, $after_id) {
			if($after_id == 0) {
				$min = $entity::min('position');
				$entity->save(array('position' => $min-1));
			}
			else {
				$i = 0;
				foreach($entity::all() as $one) {
					if($one->id == $entity->id)
						continue;

					$one->save(array('position' => $i++));
					if($one->id == $after_id)
						$entity->save(array('position' => $i++));
				}
			}
		});

		#$article->previous()
		$entityDefinition->addMethod('previous', function($entity) {
			$res = $entity::where(array('position < ?' => $this->position))->orderBy('position DESC')->first();
			if($res)
				return $res;
			return false;
		});

		#$article->next()
		$entityDefinition->addMethod('next', function($entity) {
			$res = $entity::where(array('position > ?' => $this->position))->orderBy('position ASC')->first();
			if($res)
				return $res;
			return false;
		});

		$entityDefinition->hook('asgardadmin', function($chain, $admin_controller) use($entityDefinition) {
			$entityName = $admin_controller::getEntity();
			
			try {
				$admin_controller::addHook(array(
					'route'			=>	':id/promote',
					'name'			=>	'asgard_'.$entityName.'_promote',
					'controller'	=>	'Asgard\Behaviors\Controllers\SortableBehaviorController',
					'action'			=>	'promote'
				));
				$admin_controller::addHook(array(
					'route'			=>	':id/demote',
					'name'			=>	'asgard_'.$entityName.'_demote',
					'controller'	=>	'Asgard\Behaviors\Controllers\SortableBehaviorController',
					'action'			=>	'demote'
				));
				$entityDefinition->hook('asgard_actions', array('\Asgard\Behaviors\SortableBehavior', 'sortable'));
			} catch(\Exception $e) {}#if the admincontroller does not exist for this Entity
		});

		$entityDefinition->hook('behaviors_presave', function($chain, $entity) {
			if($entity->isNew()) {
				try {
					$last = $entity::orderBy('position ASC')->first();
					$entity->position = $last->position+1;
				} catch(\Exception $e) {
					$entity->position = 0;
				}
			}
		});
	}

	public static function sortable($chain, $entity) {
		return '<a href="'.\Asgard\Core\App::get('url')->url_for('asgard_'.get_class($entity).'_promote', array('id' => $entity->id), false).'">'.__('Promote').'</a> | <a href="'.\Asgard\Core\App::get('url')->url_for('asgard_'.get_class($entity).'_demote', array('id' => $entity->id), false).'">'.__('Demote').'</a> | ';
	}
}