<?php
/**
 * CakePHP Tags
 *
 * Copyright 2009 - 2010, Cake Development Corporation
 *                        1785 E. Sahara Avenue, Suite 490-423
 *                        Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright 2009 - 2010, Cake Development Corporation
 * @link      http://codaset.com/cakedc/migrations/
 * @package   plugins.tags
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Short description for class.
 *
 * @package		tags
 * @subpackage	tests.cases
 */

App::import('Model', 'Tags.Tagged');

class TaggedTestCase extends CakeTestCase {
/**
 *
 */
	public $Tagged = null;

/**
 *
 */
	public $fixtures = array(
		'plugin.tags.tagged',
		'plugin.tags.tag',
		'plugin.tags.article');

/**
 * 
 *
 * @return void
 * @access public
 */
	public function startTest() {
		$this->Tagged = ClassRegistry::init('Tags.Tagged');
	}

/**
 * 
 *
 * @return void
 * @access public
 */
	public function endTest() {
		unset($this->Tagged);
		ClassRegistry::flush(); 
	}

/**
 *
 */
	function testTaggedInstance() {
		$this->assertTrue(is_a($this->Tagged, 'Tagged'));
	}

/**
 *
 */
	function testTaggedFind() {
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
				'created' => '2008-12-02 12:32:31',
				'modified' => '2008-12-02 12:32:31'));

		$this->assertEqual($result, $expected);
	}

/**
 *
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
		$this->assertEqual($result, 2);
	}

}
?>