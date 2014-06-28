<?php
namespace Asgard\Behaviors\Tests;

class BehaviorsTest extends \PHPUnit_Framework_TestCase {
	protected static $app;

	public static function setUpBeforeClass() {
		static::$app = $app = new \Asgard\Container\Container;
		$app['kernel'] = new \Asgard\Core\Kernel();
		$app['config'] = new \Asgard\Config\Config;
		$app['config']->set('locale', 'en');
		$app['config']->set('locales', array('fr', 'en'));
		$app['hooks'] = new \Asgard\Hook\HooksManager($app);
		$app['cache'] = new \Asgard\Cache\NullCache;
		$app['html'] = new \Asgard\Http\Utils\Html(new \Asgard\Http\Request);
		$app['rulesregistry'] = new \Asgard\Validation\RulesRegistry;
		$app['entitiesmanager'] = new \Asgard\Entity\EntitiesManager($app);
		$app['db'] = new \Asgard\Db\DB(array(
			'database' => 'asgard',
			'user' => 'root',
			'password' => '',
			'host' => 'localhost'
		));
		\Asgard\Entity\Entity::setApp($app);
		static::$app = $app;

		$db = new \Asgard\Db\DB(array(
			'host' => 'localhost',
			'user' => 'root',
			'password' => '',
			'database' => 'asgard',
		));
		$schema = new \Asgard\Db\Schema($db);
		$schema->dropAll();
		$mm = new \Asgard\Orm\ORMMigrations($app);
		$mm->autoMigrate('Asgard\Behaviors\Tests\Fixtures\News', new \Asgard\Db\Schema($db));
		Fixtures\News::create(array('id'=>1, 'title'=>'a', 'content'=>'a', 'published'=>true));
		Fixtures\News::create(array('id'=>2, 'title'=>'a', 'content'=>'a', 'published'=>true));
		Fixtures\News::create(array('id'=>3, 'title'=>'a', 'content'=>'a', 'published'=>false));
	}

	protected static function getApp() {
		return static::$app;
	}
	
	#page
	public function testPage() {
		$this->assertTrue(Fixtures\News::getDefinition()->hasBehavior('Asgard\Behaviors\MetasBehavior'));
		$this->assertTrue(Fixtures\News::getDefinition()->hasBehavior('Asgard\Behaviors\SlugifyBehavior'));
	}

	#metas
	public function testMetas() {
		$this->assertTrue(Fixtures\News::hasProperty('meta_title'));
		$this->assertTrue(Fixtures\News::hasProperty('meta_description'));
		$this->assertTrue(Fixtures\News::hasProperty('meta_keywords'));

		$news = new Fixtures\News(array(
			'title' => 'Test',
			'content' => 'Test',
			'meta_title' => 'Test Meta Title',
			'meta_description' => 'Test Meta Description',
			'meta_keywords' => 'Test Meta Keywords',
		));

		$news->showMetas();

		$this->assertEquals('Test Meta Title', static::getApp()['html']->getTitle());
		$this->assertEquals('Test Meta Description', static::getApp()['html']->getDescription());
		$this->assertEquals('Test Meta Keywords', static::getApp()['html']->getKeywords());
	}

	#slugify
	public function testSlugify() {
		$this->assertTrue(Fixtures\News::hasProperty('slug'));
		$news = new Fixtures\News(array(
			'title' => 'Test Title',
			'content' => 'Test Content',
		));
		$this->assertEquals('test-title', $news->slug());
		$news->slug = 'test';
		$this->assertEquals('test', $news->slug());
	}

	#timestamps
	public function testTimestamps() {
		$this->assertTrue(Fixtures\News::hasProperty('created_at'));
		$this->assertTrue(Fixtures\News::hasProperty('updated_at'));

		$news = new Fixtures\News(array(
			'title' => 'Test Title',
			'content' => 'Test Content',
		));
		$this->assertEquals(date('d/m/Y H:i:s'), $news->created_at->format('d/m/Y H:i:s'));
		$this->assertEquals(date('d/m/Y H:i:s'), $news->updated_at->format('d/m/Y H:i:s'));

		$news = new Fixtures\News(array(
			'title' => 'Test Title',
			'content' => 'Test Content',
			'created_at' => '2009-09-09 09:09:09',
			'updated_at' => '2009-09-09 09:09:09',
		));
		$this->assertEquals('09/09/2009 09:09:09', $news->updated_at->format('d/m/Y H:i:s'));
		$news->title = 'test';
		$news->save();
		$this->assertLessThan(2, time()-$news->updated_at->format('U'));
	}

	#publish
	public function testPublish() {
		$this->assertTrue(Fixtures\News::hasProperty('published'));

		$this->assertCount(3, Fixtures\News::published()->get());
		$this->assertEquals(null, Fixtures\News::loadPublished(3));
		$this->assertInstanceOf('Asgard\Behaviors\Tests\Fixtures\News', Fixtures\News::loadPublished(2));

		#todo test de l'admin
	}

	#sortable
	public function test6() {
	}
}

// class SaveBehavior extends \Asgard\Entity\Behavior {
// 	public function call_save($entity) {
// 		$entity::trigger('save', array($entity));
// 	}
// }