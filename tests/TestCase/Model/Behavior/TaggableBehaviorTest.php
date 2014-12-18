<?php
/**
 * Copyright 2009-2014, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2009-2014, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace Tags\Test\TestCase\Model\Behavior;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

/**
 * Article model
 *
 * @package tags
 * @subpackage tags.tests.cases.behaviors
 */
class ArticlesTable extends Table {
	public function initialize(array $config) {
		$this->table('articles');
		$this->addBehavior('Tags.Taggable');
	}
}

/**
 * TaggableTest
 *
 * @package tags
 * @subpackage tags.tests.cases.behaviors
 */
class TaggableBehaviorTest extends TestCase {

/**
 * Plugin name used for fixtures loading
 *
 * @var string
 */
	public $plugin = 'tags';

/**
 * Holds the instance of the model
 *
 * @var mixed $UsersAddon
 */
	public $Article = null;

/**
 * Fixtures associated with this test case
 *
 * @var array
 * @return void
 */
	public $fixtures = array(
		'plugin.tags.tagged',
		'plugin.tags.tag',
		'plugin.tags.article'
	);

/**
 * Method executed before each test
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Article = TableRegistry::get('Articles');
		Configure::write('Config.language', 'eng');
		$this->Article->addBehavior('Tags.Taggable');
	}

/**
 * Method executed after each test
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->Article);
		TableRegistry::clear();
	}

/**
 * Test the occurrence cache
 *
 * @return void
 */
	public function testOccurrenceCache() {
		$resultBefore = $this->Article->Tag->find('all', array(
			'contain' => array(
				'Tagged'
			),
			'conditions' => array(
				'keyname' => 'cakephp'
			)
		))->first();

		// adding a new record with the cakephp tag to increase the occurrence
		$entity = $this->Article->newEntity(array('title' => 'Test Article', 'tags' => 'cakephp, php'));
		$this->Article->save($entity, false);

		$resultAfter = $this->Article->Tag->find('all', array(
			'contain' => array(),
			'conditions' => array(
				'Tag.keyname' => 'cakephp'
			)
		))->first();

		$this->assertEquals($resultAfter['Tag']['occurrence'] - $resultBefore['Tag']['occurrence'], 1);

		// updating the record to not have the cakephp tag anymore, decreases the occurrence
		$entity = $this->Article->newEntity(array('id' => $this->Article->id, 'title' => 'Test Article', 'tags' => 'php, something, else'));
		$entity->isNew(false);
		$this->Article->save($entity, false);
		$resultAfter = $this->Article->Tag->find('all', array(
			'contain' => array(),
			'conditions' => array(
				'Tag.keyname' => 'cakephp'
			)
		))->first();
		$this->assertEquals($resultAfter['Tag']['occurrence'], 1);
	}

/**
 * Testings saving of tags trough the specified field in the tagable model
 *
 * @return void
 */
	public function testTagSaving() {
		$data['id'] = 'article-1';
		$data['tags'] = 'foo, bar, test';

		$data = $this->Article->newEntity($data);
		$this->Article->save($data, false);
		$result = $this->Article->find('all', array(
			'conditions' => array(
				'id' => 'article-1'
			)
		))->first;
		$this->assertTrue(!empty($result['Article']['tags']));

		$data['tags'] = 'foo, developer, developer, php';
		$data = $this->Article->newEntity($data);
		$this->Article->save($data, false);
		$result = $this->Article->find('all', array(
			'contain' => array('Tag'),
			'conditions' => array(
				'id' => 'article-1'
			)
		))->first();

		$this->assertTrue(!empty($result['Article']['tags']));
		$this->assertEquals(3, count($result['Tag']));


		$data['tags'] = 'cakephp:foo, developer, cakephp:developer, cakephp:php';
		$data = $this->Article->newEntity($data);
		$this->Article->save($data, false);
		$result = $this->Article->Tag->find('all', array(
			'recursive' => -1,
			'order' => 'Tag.identifier DESC, Tag.name ASC',
			'conditions' => array(
				'Tag.identifier' => 'cakephp')));

		$result = Set::extract($result, '{n}.Tag.keyname');
		$this->assertEquals($result, array(
			'developer', 'foo', 'php'));

		$this->assertFalse($this->Article->saveTags('foo, bar', null));
		$this->assertFalse($this->Article->saveTags(array('foo', 'bar'), 'something'));
	}

/**
 * Tests that toggling taggedCounter will update the time_tagged counter in the tagged table
 *
 * @return void
 */
	public function testSaveTimesTagged() {
		$this->Article->Behaviors->Taggable->settings['Article']['taggedCounter'] = true;
		$tags = 'foo, bar , test';
		$this->assertTrue($this->Article->saveTags($tags, 'article-1', false));
		$this->assertTrue($this->Article->saveTags($tags, 'article-1', false));

		$result = $this->Article->Tagged->find('all', array(
			'conditions' => array('model' => 'Article'),
			'contain' => array('Tag'),
		));
		$fooCount = Set::extract('/Tag[keyname=foo]/../Tagged/times_tagged', $result);
		$this->assertEquals($fooCount, array(2));

		$barCount = Set::extract('/Tag[keyname=bar]/../Tagged/times_tagged', $result);
		$this->assertEquals($barCount, array(2));

		$testCount = Set::extract('/Tag[keyname=test]/../Tagged/times_tagged', $result);
		$this->assertEquals($testCount, array(2));
	}

/**
 * Testing Taggable::tagArrayToString()
 *
 * @return void
 */
	public function testTagArrayToString() {
		$data['id'] = 'article-1';
		$data['tags'] = 'foo, bar, test';
		$data = $this->Article->newEntity($data);
		$data->isNew(false);
		$this->Article->save($data, false);
		$result = $this->Article->find('all', array(
			'conditions' => array(
				'id' => 'article-1'
			)
		))->first();
		$result = $this->Article->tagArrayToString($result['Tag']);
		$this->assertTrue(!empty($result));
		$this->assertInternalType('string', $result);
		$this->assertEquals($result, 'test, bar, foo');

		$result = $this->Article->tagArrayToString();
		$this->assertTrue(empty($result));
		$this->assertInternalType('string', $result);

		$data['tags'] = 'cakephp:foo, cakephp:bar, foo, bar';
		$data = $this->Article->newEntity($data);
		$this->Article->save($data, false);
		$result = $this->Article->find('first', array(
			'conditions' => array(
				'id' => 'article-1'
			)
		));

		$result = $this->Article->tagArrayToString($result['Tag']);
		$this->assertTrue(!empty($result));
		$this->assertInternalType('string', $result);
		$this->assertEquals($result, 'cakephp:bar, cakephp:foo, bar, foo');
	}

/**
 * Testings Taggable::multibyteKey()
 *
 * @return void
 */
	public function testMultibyteKey() {
		$result = $this->Article->multibyteKey('this is _ a Nice ! - _ key!');
		$this->assertEquals('thisisanicekey', $result);

		$result = $this->Article->multibyteKey('Äü-Ü_ß');
		$this->assertEquals('äüüß', $result);
	}

/**
 * testAfterFind callback method
 *
 * @return void
 */
	public function testAfterFind() {
		$data['id'] = 'article-1';
		$data['tags'] = 'foo, bar, test';
		$data = $this->Article->newEntity($data);
		$this->Article->save($data, false);

		$result = $this->Article->find('all', array(
			'conditions' => array(
				'id' => 'article-1'
			)
		))->first();
		$this->assertTrue(isset($result['Tag']));

		$this->Article->Behaviors->Taggable->settings['Article']['unsetInAfterFind'] = true;
		$result = $this->Article->find('all', array(
			'conditions' => array(
				'id' => 'article-1'
			)
		))->first();
		$this->assertTrue(!isset($result['Tag']));
	}

/**
 * testAfterFindFields
 *
 * @return void
 */
	public function testAfterFindFields() {
		$this->Article->removeBehavior('Taggable');
		$results = $this->Article->find('all', array(
			'recursive' => -1,
			'fields' => array('id')
		))->first();
		$expected = array($this->Article->alias => array('id' => 'article-1'));
		$this->assertIdentical($results, $expected);
	}

/**
 * testGettingTagCloudThroughAssociation
 *
 * @link http://cakedc.lighthouseapp.com/projects/59622/tickets/6-tag-cloud-helper
 * @return void
 */
	public function testGettingTagCloudThroughAssociation() {
		$result = $this->Article->Tagged->find('cloud');
		$this->assertTrue(is_array($result) && !empty($result));
	}

/**
 * testSavingEmptyTagsDeleteAssociatedTags
 *
 * @return void
 */
	public function testSavingEmptyTagsDeleteAssociatedTags() {
		$this->Article->Behaviors->Taggable->settings['Article']['deleteTagsOnEmptyField'] = true;
		$data = $this->Article->findById('article-1');
		$data['Article']['tags'] = '';
		$this->Article->save($data, false);
		$result = $this->Article->find('first', array(
			'conditions' => array('id' => 'article-1')
		));

		$this->assertEmpty($result['Tag']);
	}

/**
 * testSavingEmptyTagsDoNotDeleteAssociatedTags
 *
 * @return void
 */
	public function testSavingEmptyTagsDoNotDeleteAssociatedTags() {
		$this->Article->Behaviors->Taggable->settings['Article']['deleteTagsOnEmptyField'] = false;
		$data = $this->Article->findById('article-1');
		$data['Article']['tags'] = '';
		$this->Article->save($data, false);
		$result = $this->Article->find('first', array(
			'conditions' => array('id' => 'article-1')
		));

		$this->assertNotEmpty($result['Tag']);
	}

/**
 * testSavingTagsDoesNotCreateEmptyRecords
 *
 * @return void
 */
	public function testSavingTagsDoesNotCreateEmptyRecords() {
		$count = $this->Article->Tag->find('count', array(
			'conditions' => array(
				'Tag.name' => '',
				'Tag.keyname' => '',
			)
		));
		$this->assertEquals($count, 0);

		$data['id'] = 'article-1';
		$data['tags'] = 'foo, bar, test';
		$this->Article->save($data, false);
		$result = $this->Article->find('first', array(
			'conditions' => array(
				'id' => 'article-1')
		));

		$count = $this->Article->Tag->find('count', array(
			'conditions' => array(
				'Tag.name' => '',
				'Tag.keyname' => '',
			)
		));
		$this->assertEquals($count, 0);
	}

/**
 * testSavingTagsWithDefferentIdentifier
 *
 * @return void
 */
	public function testSavingTagsWithDifferentIdentifier() {
		$data = $this->Article->findById('article-1');
		$data['Article']['tags'] = 'foo:cakephp, bar:cakephp';
		$data = $this->Article->newEntity($data);
		$this->Article->save($data);
		$data = $this->Article->findById('article-1');
		$this->assertEquals('bar:cakephp, foo:cakephp', $data['Article']['tags']);
	}

/**
 * testDeletingMoreThanOneTagAtATime
 *
 * @link https://github.com/CakeDC/tags/issues/86
 * @return void
 */
	public function testDeletingMoreThanOneTagAtATime() {
		// Adding five tags for testing
		$data = array(
			'Article' => array(
				'id' => 'article-test-delete-tags',
				'tags' => 'foo, bar, test, second, third',
			)
		);
		$entity = $this->Article->newEntity($data);
		$this->Article->save($entity, false);
		$result = $this->Article->find('all', array(
			'conditions' => array(
				'id' => 'article-test-delete-tags'
			)
		))->first();
		$this->assertEquals($result['Article']['tags'], 'third, second, test, bar, foo');
		// Removing three of the five previously added tags
		$result['Article']['tags'] = 'third, second';
		$this->Article->save($result, false);
		$result = $this->Article->find('all', array(
			'conditions' => array(
				'id' => 'article-test-delete-tags'
			)
		))->first();
		$this->assertEquals($result['Article']['tags'], 'second, third');
		// Removing all tags, empty string - WON'T work as expected because of deleteTagsOnEmptyField
		$result['Article']['tags'] = '';
		$this->Article->save($result, false);
		$result = $this->Article->find('all', array(
			'conditions' => array(
				'id' => 'article-test-delete-tags'
			)
		))->first();
		$this->assertEquals($result['Article']['tags'], 'third, second');
		// Now with deleteTagsOnEmptyField
		$this->Article->addBehavior('Tags.Taggable', array(
			'deleteTagsOnEmptyField' => true
		));
		$result['Article']['tags'] = '';
		$this->Article->save($result, false);
		$result = $this->Article->find('all', array(
			'conditions' => array(
				'id' => 'article-test-delete-tags'
			)
		))->first();
		$this->assertEquals($result['Article']['tags'], '');
	}

}
