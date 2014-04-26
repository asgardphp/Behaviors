<?php
namespace Asgard\Behaviors\Tests;

class BehaviorsTest extends \PHPUnit_Framework_TestCase {
	public static function setUpBeforeClass() {
		if(!defined('_ENV_'))
			define('_ENV_', 'test');
		require_once(_VENDOR_DIR_.'autoload.php');

		\Asgard\Core\App::instance(true)->config->set('bundles', array(
			__DIR__.'/..',
		))
		->set('bundlesdirs', array());
		\Asgard\Core\App::loadDefaultApp();

		\Asgard\Core\App::get('hook')->hook('behaviors_pre_load', function($chain, $entityDefinition) {
			if(!isset($entityDefinition->behaviors['Asgard\Behaviors\TimestampsBehavior']))
				$entityDefinition->behaviors['Asgard\Behaviors\TimestampsBehavior'] = true;
		});
	}
	
	#page
	public function test1() {
		$this->assertTrue(\Asgard\Behaviors\Tests\Entities\News::getDefinition()->behaviors['Asgard\Behaviors\MetasBehavior']);
		// $this->assertTrue(Asgard\Behaviors\Tests\Entities\News::getDefinition()->behaviors['Asgard\Behaviors\SlugifyBehavior']);
	}

	#metas
	public function test2() {
		$this->assertTrue(\Asgard\Behaviors\Tests\Entities\News::hasProperty('meta_title'));
		$this->assertTrue(\Asgard\Behaviors\Tests\Entities\News::hasProperty('meta_description'));
		$this->assertTrue(\Asgard\Behaviors\Tests\Entities\News::hasProperty('meta_keywords'));

		$news = new \Asgard\Behaviors\Tests\Entities\News(array(
			'title' => 'Test',
			'content' => 'Test',
			'meta_title' => 'Test Meta Title',
			'meta_description' => 'Test Meta Description',
			'meta_keywords' => 'Test Meta Keywords',
		));

		$news->showMetas();

		$this->assertEquals('Test Meta Title', \Asgard\Core\App::instance()->html->getTitle());
		$this->assertEquals('Test Meta Description', \Asgard\Core\App::instance()->html->getDescription());
		$this->assertEquals('Test Meta Keywords', \Asgard\Core\App::instance()->html->getKeywords());
	}

	#slugify
	public function test3() {
		$this->assertTrue(\Asgard\Behaviors\Tests\Entities\News::hasProperty('slug'));
		$news = new \Asgard\Behaviors\Tests\Entities\News(array(
			'title' => 'Test Title',
			'content' => 'Test Content',
		));
		$this->assertEquals('test-title', $news->slug());
		$news->slug = 'test';
		$this->assertEquals('test', $news->slug());
	}

	#timestamps
	public function test4() {
		$this->assertTrue(\Asgard\Behaviors\Tests\Entities\News::hasProperty('created_at'));
		$this->assertTrue(\Asgard\Behaviors\Tests\Entities\News::hasProperty('updated_at'));

		$news = new \Asgard\Behaviors\Tests\Entities\News(array(
			'title' => 'Test Title',
			'content' => 'Test Content',
		));
		$this->assertEquals(date('d/m/Y H:i:s'), $news->created_at->format('d/m/Y H:i:s'));
		$this->assertEquals(date('d/m/Y H:i:s'), $news->updated_at->format('d/m/Y H:i:s'));

		$news = new \Asgard\Behaviors\Tests\Entities\News(array(
			'title' => 'Test Title',
			'content' => 'Test Content',
			'created_at' => '2009-09-09 09:09:09',
			'updated_at' => '2009-09-09 09:09:09',
		));
		$this->assertEquals('09/09/2009 09:09:09', $news->updated_at->format('d/m/Y H:i:s'));
		$news->title = 'test';
		$this->assertEquals(date('d/m/Y H:i:s'), $news->updated_at->format('d/m/Y H:i:s'));
	}

	#publish
	public function test5() {
		// \DB::import(realpath(__dir__.'/sql/test5.sql'));

		// $this->assertTrue(Asgard\Behaviors\Tests\Entities\News::hasProperty('published'));
		// $this->assertEquals(1, Asgard\Behaviors\Tests\Entities\News::count());
		// $this->assertEquals(1, Asgard\Behaviors\Tests\Entities\News::published()->count());
	}

	#sortable
	public function test6() {
	}
}

// class NullClass {
// 	public function __call($name, $args) {return $this;}
// 	public static function __callStatic($name, $args) {}
// 	public function __get($name) {}
// 	public function __set($name, $value) {}
// 	public function __isset($name) {}
// 	public function __unset($name) {}
// }