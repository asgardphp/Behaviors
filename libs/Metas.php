<?php
namespace Coxis\Behaviors\Libs;

class Metas {
	public static function set($entity) {
		HTML::setTitle($entity->meta_title!='' ? html_entity_decode($entity->meta_title):html_entity_decode($entity));
		HTML::setKeywords($entity->meta_keywords);
		HTML::setDescription($entity->meta_description);
	}
}