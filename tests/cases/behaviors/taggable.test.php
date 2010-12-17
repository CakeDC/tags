<?php
/**
 * Copyright 2009-2010, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2009-2010, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::import('Core', 'Model');

/**
 * Article model
 *
 * @package tags
 * @subpackage tags.tests.cases.behaviors
 */
class Article extends CakeTestModel {

/**
 * Use table
 *
 * @var string
 */
	public $useTable = 'articles';

/**
 * Belongs to associations
 *
 * @var array
 */
	public $belongsTo = array();

/**
 * HABTM associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array();

/**
 * Has Many Associations
 *
 * @var array
 */
	public $hasMany = array();

/**
 * Has One associations
 *
 * @var array
 */
	public $hasOne = array();

/**
 * Behaviors
 *
 * @var array
 */
	public $actsAs = array('Tags.Taggable');
}

/**
 * TaggableTest
 *
 * @package tags
 * @subpackage tags.tests.cases.behaviors
 */
class TaggableTest extends CakeTestCase {

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
		'plugin.tags.article');

/**
 * Method executed before each test
 *
 * @return void
 */
	public function setUp() {
		$this->Article = ClassRegistry::init('Article');
		$this->Article->Behaviors->attach('Tags.Taggable', array());
	}

/**
 * Method executed after each test
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Article);
		ClassRegistry::flush();
	}

/**
 * Testings saving of tags trough the specified field in the tagable model
 *
 * @return void
 */
	public function testTagSaving() {
		$data['id'] = 1;
		$data['tags'] = 'foo, bar, test';
		$this->Article->save($data, false);
		$result = $this->Article->find('first', array(
			'conditions' => array(
			'id' => 1)));
		$this->assertTrue(!empty($result['Article']['tags']));

		$data['tags'] = 'foo, developer, developer, php';
		$this->Article->save($data, false);
		$result = $this->Article->find('first', array(
			'contain' => array('Tag'),
			'conditions' => array(
				'id' => 1)));
		$this->assertTrue(!empty($result['Article']['tags']));


		$data['tags'] = 'cakephp:foo, developer, cakephp:developer, cakephp:php';
		$this->Article->save($data, false);
		$result = $this->Article->Tag->find('all', array(
			'recursive' => -1,
			'order' => 'Tag.identifier DESC',
			'conditions' => array(
				'Tag.identifier' => 'cakephp')));
		$result = Set::extract($result, '{n}.Tag.keyname');
		$this->assertEqual($result, array(
			'developer', 'foo', 'php'));


		$this->assertFalse($this->Article->saveTags('foo, bar', null));
		$this->assertFalse($this->Article->saveTags(array('foo', 'bar'), 'something'));
	}

/**
 * Tests that toggling taggedCounter will update the time_tagged counter in the tagged table 
 *
 * @return void
 */
	function testSaveTimesTagged() {
		$this->Article->Behaviors->Taggable->settings['Article']['taggedCounter'] = true;
		$tags = 'foo, bar , test';
		$this->assertTrue($this->Article->saveTags($tags, 1, false));
		$this->assertTrue($this->Article->saveTags($tags, 1, false));

		$result =  $this->Article->Tagged->find('all', array(
			'conditions' => array('model' => 'Article')));
		
		$fooCount = Set::extract('/Tag[keyname=foo]/../Tagged/times_tagged', $result);
		$this->assertEqual($fooCount, array(2));
		
		$barCount = Set::extract('/Tag[keyname=bar]/../Tagged/times_tagged', $result);
		$this->assertEqual($barCount, array(2));

		$testCount = Set::extract('/Tag[keyname=test]/../Tagged/times_tagged', $result);
		$this->assertEqual($testCount, array(2));
	}

/**
 * Testings Taggable::tagArrayToString()
 *
 * @return void
 */
	public function testTagArrayToString() {
		$data['id'] = 1;
		$data['tags'] = 'foo, bar, test';
		$this->Article->save($data, false);
		$result = $this->Article->find('first', array(
			'conditions' => array(
				'id' => 1)));
		$result = $this->Article->tagArrayToString($result['Tag']);
		$this->assertTrue(!empty($result));
		$this->assertInternalType('string', $result);

		$result = $this->Article->tagArrayToString();
		$this->assertTrue(empty($result));
		$this->assertInternalType('string', $result);
	}

/**
 * Testings Taggable::multibyteKey()
 *
 * @return void
 */
	public function testMultibyteKey() {
		$result = $this->Article->multibyteKey('this is _ a Nice ! - _ key!');
		$this->assertEqual('thisisanicekey', $result);

		$result = $this->Article->multibyteKey('Äü-Ü_ß');
		$this->assertEqual('äüüß', $result);
	}

/**
 * testAfterFind callback method
 *
 * @return void
 */
	public function testAfterFind() {
		$data['id'] = 1;
		$data['tags'] = 'foo, bar, test';
		$this->Article->save($data, false);

		$result = $this->Article->find('first', array(
			'conditions' => array(
				'id' => 1)));
		$this->assertTrue(isset($result['Tag']));

		$this->Article->Behaviors->Taggable->settings['Article']['unsetInAfterFind'] = true;
		$result = $this->Article->find('first', array(
			'conditions' => array(
				'id' => 1)));
		$this->assertTrue(!isset($result['Tag']));
	}

/**
 * testAfterFindFields
 *
 * @return void
 */
	public function testAfterFindFields() {
		$this->Article->Behaviors->detach('Taggable');
		$results = $this->Article->find('first', array(
			'recursive' => -1,
			'fields' => array('id')));
		$expected = array($this->Article->alias => array('id' => '1'));
		$this->assertIdentical($results, $expected);
	}
}
