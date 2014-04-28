<?php
namespace Asgard\Behaviors;

class SlugifyBehavior implements \Asgard\Core\Behavior {
	public static function load($entityDefinition, $params=null) {
		$slug_from = isset($params) ? $params:null;

		$entityDefinition->addProperty('slug', array('type' => 'text', 'required' => false));

		$entityDefinition->addMethod('slug', function($entity) use($slug_from) {
			if($entity->slug)
				return $entity->slug;
			if($slug_from !== null && $entity->hasProperty($slug_from))
				return \Asgard\Utils\Tools::slugify($entity->{$slug_from});
			elseif(method_exists($entity, '__toString'))
				return \Asgard\Utils\Tools::slugify($entity->__toString());
		});

		$entityDefinition->hook('presave', function($chain, $entity) {
			if($entity->isNew())
				$entity->slug = \Asgard\Utils\Tools::slugify($entity);
			else {
				$inc = 1;
				do {
					$entity->slug = \Asgard\Utils\Tools::slugify($entity).($inc < 2 ? '':'-'.$inc);
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