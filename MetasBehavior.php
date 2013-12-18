<?php
namespace Coxis\Behaviors;

class MetasBehavior implements \Coxis\Core\Behavior {
	public static function load($entityDefinition, $params=null) {
		$entityDefinition->addProperty('meta_title', array('type' => 'text', 'required' => false));
		$entityDefinition->addProperty('meta_description', array('type' => 'text', 'required' => false));
		$entityDefinition->addProperty('meta_keywords', array('type' => 'text', 'required' => false));

		#$article->showMetas()
		$entityDefinition->addMethod('showMetas', function($entity) {
			HTML::setTitle($entity->meta_title!='' ? html_entity_decode($entity->meta_title):html_entity_decode($entity));
			HTML::setKeywords($entity->meta_keywords);
			HTML::setDescription($entity->meta_description);
		});
	}
}