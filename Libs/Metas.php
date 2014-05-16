<?php
namespace Asgard\Behaviors\Libs;

class Metas {
	public static function set(\Asgard\Entity\Entity $entity) {
		HTML::setTitle($entity->meta_title!='' ? html_entity_decode($entity->meta_title):html_entity_decode($entity));
		HTML::setKeywords($entity->meta_keywords);
		HTML::setDescription($entity->meta_description);
	}
}