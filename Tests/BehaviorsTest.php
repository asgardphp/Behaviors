<?php
namespace Asgard\Behaviors\Tests;

class BehaviorsTest extends \PHPUnit_Framework_TestCase {
	protected static $container;

	public static function setUpBeforeClass() {
		$container = new \Asgard\Container\Container;
		$container['hooks'] = new \Asgard\Hook\HooksManager($container);
		$container['html'] = new \Asgard\Http\Utils\HTML(new \Asgard\Http\Request);
		$container['db'] = new \Asgard\Db\DB(array(
			'database' => 'asgard',
			'user' => 'root',
			'password' => '',
			'host' => 'localhost'
		));
		$container->register('orm', function($container, $entityClass, $locale, $prefix, $dataMapper) {
			return new \Asgard\Orm\ORM($entityClass, $locale, $prefix, $dataMapper);
		});

		$entitiesManager = $container['entitiesmanager'] = new \Asgard\Entity\EntitiesManager($container);
		$entitiesManager->setValidatorFactory(new \Asgard\Validation\ValidatorFactory);
		#set the EntitiesManager static instance for activerecord-like entities (e.g. new Article or Article::find())
		\Asgard\Entity\EntitiesManager::setInstance($entitiesManager);

		$container->register('datamapper', function($container) {
			return new \Asgard\Orm\DataMapper(
				$container['db'],
				$container['entitiesManager'],
				'en',
				'',
				new \Asgard\Orm\ORMFactory
			);
		});

		static::$container = $container;

		$db = new \Asgard\Db\DB(array(
			'host' => 'localhost',
			'user' => 'root',
			'password' => '',
			'database' => 'asgard',
		));
		$schema = new \Asgard\Db\Schema($db);
		$schema->dropAll();
		$mm = new \Asgard\Orm\ORMMigrations($container['dataMapper']);
		$mm->autoMigrate($entitiesManager->get('Asgard\Behaviors\Tests\Fixtures\News'), new \Asgard\Db\Schema($db));
		Fixtures\News::create(array('id'=>1, 'title'=>'a', 'content'=>'a', 'published'=>true));
		Fixtures\News::create(array('id'=>2, 'title'=>'a', 'content'=>'a', 'published'=>true));
		Fixtures\News::create(array('id'=>3, 'title'=>'a', 'content'=>'a', 'published'=>false));
	}

	protected static function getContainer() {
		return static::$container;
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

		$this->assertEquals('Test Meta Title', static::getContainer()['html']->getTitle());
		$this->assertEquals('Test Meta Description', static::getContainer()['html']->getDescription());
		$this->assertEquals('Test Meta Keywords', static::getContainer()['html']->getKeywords());
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
		$created_at = $news->created_at->format('U');
		$updated_at = $news->updated_at->format('U');
		$this->assertTrue($created_at == time() || $created_at == (time()-1));
		$this->assertTrue($updated_at == time() || $updated_at == (time()-1));

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