<?php
namespace Coxis\Behaviors;

class SlugifyBehavior implements \Coxis\Core\Behavior {
	public static function load($entityDefinition, $params=null) {
		$entityDefinition->addProperty('slug', array('type' => 'text', 'required' => false, 'editable'=>false));

		$entityDefinition->hook('behaviors_presave', function($chain, $entity) {
			if($entity->isNew())
				$entity->slug = \Coxis\Utils\Tools::slugify($entity);
			else {
				$inc = 1;
				do {
					$entity->slug = \Coxis\Utils\Tools::slugify($entity).($inc < 2 ? '':'-'.$inc);
					$inc++;
				}
				while($entity::where(array(
					'id != ?' => $entity->id,
					'slug' => $entity->slug,
				))->count());
			}
		});
	}
}