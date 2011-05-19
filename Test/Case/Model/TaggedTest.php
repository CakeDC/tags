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

App::import('Model', 'Tags.Tagged');

/**
 * Short description for class.
 *
 * @package tags
 * @subpackage tags.tests.cases.models
 */
class TaggedTestCase extends CakeTestCase {

/**
 * Tagged model
 *
 * @var Tagged
 */
	public $Tagged = null;

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.tags.tagged',
		'plugin.tags.tag',
		'plugin.tags.article');

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		$this->Tagged = ClassRegistry::init('Tags.Tagged');
	}

/**
 * tearDown
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Tagged);
		ClassRegistry::flush(); 
	}

/**
 * testTaggedInstance
 *
 * @return void
 */
	public function testTaggedInstance() {
		$this->assertTrue(is_a($this->Tagged, 'Tagged'));
	}

/**
 * testTaggedInstance
 *
 * @return void
 */
	public function testTaggedFind() {
		$this->Tagged->recursive = -1;
		$result = $this->Tagged->find('first');
		$this->assertTrue(!empty($result));

		$expected = array(
			'Tagged' => array(
				'id' => '49357f3f-c464-461f-86ac-a85d4a35e6b6',
				'foreign_key' => 1,
				'tag_id' => 1, //cakephp
				'model' => 'Article',
				'language' => 'eng',
				'times_tagged' => 1,
				'created' => '2008-12-02 12:32:31',
				'modified' => '2008-12-02 12:32:31'));

		$this->assertEqual($result, $expected);
	}

/**
 * testFindCloud
 *
 * @return void
 */
	public function testFindCloud() {
		$result = $this->Tagged->find('cloud', array(
			'model' => 'Article'));
		$this->assertEqual(count($result), 3);
		$this->assertTrue(isset($result[0][0]['occurrence']));
		$this->assertEqual($result[0][0]['occurrence'], 1);
	}

/**
 * Test custom _findTagged method
 * 
 * @return void
 */
	public function testFindTagged() {
		$result = $this->Tagged->find('tagged', array(
			'by' => 'cakephp',
			'model' => 'Article'));
		$this->assertEqual(count($result), 1);
		$this->assertEqual($result[0]['Article']['id'], 1);

		$result = $this->Tagged->find('tagged', array(
			'model' => 'Article'));
		$this->assertEqual(count($result), 2);

		// Test call to paginateCount by Controller::pagination()
		$result = $this->Tagged->paginateCount(array(), 1, array(
			'model' => 'Article',
			'type' => 'tagged'));
		$this->assertEqual($result, 3);
	}
}
