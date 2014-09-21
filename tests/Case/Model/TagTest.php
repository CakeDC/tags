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

namespace Tags\Test\TestCase\Model;

use Cake\TestSuite\TestCase;

/**
 * TagTest
 *
 * @package tags
 * @subpackage tags.tests.cases.models
 */
class TagTest extends TestCase {

/**
 * Tag Instance
 *
 * @var instance
 */
	public $Tag = null;

/**
 * setUp
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.tags.tagged',
		'plugin.tags.tag'
	);

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Tag = ClassRegistry::init('Tags.Tag');
	}

/**
 * tearDown
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->Tag);
	}

/**
 * testTagInstance
 *
 * @return void
 */
	public function testTagInstance() {
		$this->assertTrue(is_a($this->Tag, 'Tag'));
	}

/**
 * testTagFind
 *
 * @return void
 */
	public function testTagFind() {
		$this->Tag->recursive = -1;
		$results = $this->Tag->find('first');
		$this->assertTrue(!empty($results));

		$expected = array(
			'Tag' => array(
				'id' => 'tag-1',
				'identifier' => null,
				'name' => 'CakePHP',
				'keyname' => 'cakephp',
				'occurrence' => 1,
				'article_occurrence' => 1,
				'created' => '2008-06-02 18:18:11',
				'modified' => '2008-06-02 18:18:37'));
		$this->assertEquals($results, $expected);
	}

/**
 * testView
 *
 * @return void
 */
	public function testView() {
		$result = $this->Tag->view('cakephp');
		$this->assertTrue(is_array($result));
		$this->assertEquals($result['Tag']['keyname'], 'cakephp');

		$this->expectException('CakeException');
		$this->Tag->view('invalid-key!!!');
	}

/**
 * testAdd
 *
 * @return void
 */
	public function testAdd() {
		$result = $this->Tag->add(
			array('Tag' => array(
				'tags' => 'tag1, tag2, tag3')));
		$this->assertTrue($result);
		$result = $this->Tag->find('all', array(
			'recursive' => -1,
			'fields' => array(
				'Tag.name'
			)
		));
		$result = Set::extract($result, '{n}.Tag.name');
		$this->assertTrue(in_array('tag1', $result));
		$this->assertTrue(in_array('tag2', $result));
		$this->assertTrue(in_array('tag3', $result));

		// adding same tags again.
		$result = $this->Tag->add(
			array('Tag' => array(
				'tags' => 'tag1, tag2, tag3'
			)
		));
		$this->assertTrue($result);
	}

/**
 * testAdd
 *
 * @return void
 */
	public function testEdit() {
		$this->assertNull($this->Tag->edit('tag-1'));
		$this->assertEquals($this->Tag->data['Tag']['id'], 'tag-1');

		$data = array(
			'Tag' => array(
				'id' => 'tag-1',
				'name' => 'CAKEPHP'));
		$this->assertTrue($this->Tag->edit('tag-1', $data));

		$data = array(
			'Tag' => array(
				'id' => 'tag-1',
				'name' => 'CAKEPHP111'));
		$this->assertFalse($this->Tag->edit('tag-1', $data));

		$data = array(
			'Tag' => array(
				'id' => 'tag-1',
				'name' => 'CAKEPHP',
				'keyname' => ''));
		$this->assertEquals($this->Tag->edit('tag-1', $data), $data);

		$this->expectException('CakeException');
		$this->assertTrue($this->Tag->edit('invalid-id', array()));
	}
}
