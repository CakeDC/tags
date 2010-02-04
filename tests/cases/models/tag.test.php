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

App::import('Model', 'Tags.Tag');

class TestTag extends Tag {
	public $useDbConfig = "test_suite";
	public $cacheSources = false;
	public $hasAndBelongsToMany = array();
	public $belongsTo = array();
	public $hasOne = array();
	public $hasMany = array();
}

class TagTestCase extends CakeTestCase {
/**
 * Tag Instance
 *
 * @var instance
 * @access public
 */
	public $Tag = null;

/**
 * startTest
 *
 * @var array
 * @access public
 */
	public $fixtures = array(
		'plugin.tags.tagged',
		'plugin.tags.tag');

/**
 * startTest
 *
 * @return void
 * @access public
 */
	public function startTest() {
		$this->Tag = new TestTag();
	}

/**
 * endTest
 *
 * @return void
 * @access public
 */
	public function endTest() {
		unset($this->Tag);
	}

/**
 * testTagInstance
 *
 * @return void
 * @access public
 */
	public function testTagInstance() {
		$this->assertTrue(is_a($this->Tag, 'Tag'));
	}

/**
 * testTagFind
 *
 * @return void
 * @access public
 */
	public function testTagFind() {
		$this->Tag->recursive = -1;
		$results = $this->Tag->find('first');
		$this->assertTrue(!empty($results));

		$expected = array(
			'Tag' => array(
				'id'  => 1,
				'identifier'  => null,
				'name'  => 'CakePHP',
				'keyname'  => 'cakephp',
				'weight' => 2,
				'created'  => '2008-06-02 18:18:11',
				'modified'  => '2008-06-02 18:18:37'));
		$this->assertEqual($results, $expected);
	}

/**
 * testView
 *
 * @return void
 * @access public
 */
	public function testView() {
		$result = $this->Tag->view('cakephp');
		$this->assertTrue(is_array($result));
		$this->assertEqual($result['Tag']['keyname'], 'cakephp');

		$this->expectException('Exception');
		$this->Tag->view('invalid-key!!!');
	}

/**
 * testAdd
 *
 * @return void
 * @access public
 */
	public function testAdd() {
		$result = $this->Tag->add(
			array('Tag' => array(
				'tags' => 'tag1, tag2, tag3')));
		$this->assertTrue($result);
		$result = $this->Tag->find('all', array(
			'recursive' => -1,
			'fields' => array(
				'Tag.name')));
		$result = Set::extract($result, '{n}.Tag.name');
		$this->assertTrue(in_array('tag1', $result));
		$this->assertTrue(in_array('tag2', $result));
		$this->assertTrue(in_array('tag3', $result));
	}

/**
 * testAdd
 *
 * @return void
 * @access public
 */
	public function testEdit() {
		$this->assertNull($this->Tag->edit(1));
		$this->assertEqual($this->Tag->data['Tag']['id'], 1);
		
		$data = array(
			'Tag' => array(
				'id' => 1,
				'name' => 'CAKEPHP'));
		$this->assertTrue($this->Tag->edit(1, $data));

		$data = array(
			'Tag' => array(
				'id' => 1,
				'name' => 'CAKEPHP111'));
		$this->assertFalse($this->Tag->edit(1, $data));

		$data = array(
			'Tag' => array(
				'id' => 1,
				'name' => 'CAKEPHP',
				'keyname' => ''));
		$this->assertEqual($this->Tag->edit(1, $data), $data);
		
		$this->expectException('Exception');
		$this->assertTrue($this->Tag->edit('invalid-id', array()));
	}

}

?>