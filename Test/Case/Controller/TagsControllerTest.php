<?php
/**
 * Copyright 2009-2012, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2009-2012, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('TagsController', 'Tags.Controller');
App::uses('TagsAppController', 'Tags.Controller');

/**
 * TestTagsController
 *
 * @package tags
 * @subpackage tags.tests.cases.controllers
 */
class TestTagsController extends TagsController {

/**
 * Auto render
 *
 * @var boolean
 */
	public $autoRender = false;

/**
 * Redirect URL
 *
 * @var mixed
 */
	public $redirectUrl = null;

/**
 * Override controller method for testing
 *
 * @return void
 */
	public function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}

/**
 * Override controller method for testing
 *
 * @return void
 */
	public function render($action = null, $layout = null, $file = null) {
		$this->renderedView = $action;
	}
}

/**
 * TagsControllerTest
 *
 * @package tags
 * @subpackage tags.tests.cases.controllers
 */
class TagsControllerTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.tags.tagged',
		'plugin.tags.tag');

/**
 * Tags Controller Instance
 *
 * @return void
 */
	public $Tags = null;

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Tags = new TestTagsController(new CakeRequest(null, false));
		$this->Tags->params = array(
			'named' => array(),
			'url' => array());
		$this->Tags->constructClasses();
		$this->Tags->Session = $this->getMock('SessionComponent', array(), array(), '', false);
	}

/**
 * tearDown
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->Tags);
	}

/**
 * testTagsControllerInstance
 *
 * @return void
 */
	public function testTagsControllerInstance() {
		$this->assertTrue(is_a($this->Tags, 'TagsController'));
	}

/**
 * testIndex
 *
 * @return void
 */
	public function testIndex() {
		$this->Tags->index();
		$this->assertTrue(!empty($this->Tags->viewVars['tags']));
	}

/**
 * testIndex
 *
 * @return void
 */
	public function testView() {
		$this->Tags->view('cakephp');
		$this->assertTrue(!empty($this->Tags->viewVars['tag']));
		$this->assertEqual($this->Tags->viewVars['tag']['Tag']['keyname'], 'cakephp');

		$this->Tags->view('invalid-key-name!');
		$this->assertEqual($this->Tags->redirectUrl, '/');
	}

/**
 * testIndex
 *
 * @return void
 */
	public function testAdminView() {
		$this->Tags->admin_view('cakephp');
		$this->assertTrue(!empty($this->Tags->viewVars['tag']));
		$this->assertEqual($this->Tags->viewVars['tag']['Tag']['keyname'], 'cakephp');

		$this->Tags->admin_view('invalid-key-name!');
		$this->assertEqual($this->Tags->redirectUrl, '/');
	}

/**
 * testAdminIndex
 *
 * @return void
 */
	public function testAdminIndex() {
		$this->Tags->admin_index();
		$this->assertTrue(!empty($this->Tags->viewVars['tags']));
	}

/**
 * testAdminDelete
 *
 * @return void
 */
	public function testAdminDelete() {
		$this->Tags->Session->expects($this->at(0))
			->method('setFlash')
			->with($this->equalTo(__d('tags', 'Invalid Tag.')))
			->will($this->returnValue(true));

		$this->Tags->Session->expects($this->at(1))
			->method('setFlash')
			->with($this->equalTo(__d('tags', 'Tag deleted.')))
			->will($this->returnValue(true));


		$this->Tags->admin_delete('WRONG-ID!!!');
		$this->assertEqual($this->Tags->redirectUrl, array('action' => 'index'));

		$this->Tags->admin_delete('tag-1');
		$this->assertEqual($this->Tags->redirectUrl, array('action' => 'index'));
	}

/**
 * testAdminAdd
 *
 * @return void
 */
	public function testAdminAdd() {
		$this->Tags->data = array(
			'Tag' => array(
				'tags' => 'tag1, tag2, tag3'));
		$this->Tags->admin_add();
		$this->assertEqual($this->Tags->redirectUrl, array('action' => 'index'));
	}

/**
 * testAdminEdit
 *
 * @return void
 */
	public function testAdminEdit() {
		$this->Tags->admin_edit('tag-1');
		$tag = array(
			'Tag' => array(
				'id'  => 'tag-1',
				'identifier'  => null,
				'name'  => 'CakePHP',
				'keyname'  => 'cakephp',
				'occurrence' => 1,
				'article_occurrence' => 1,
				'created'  => '2008-06-02 18:18:11',
				'modified'  => '2008-06-02 18:18:37'));

		$this->assertEqual($this->Tags->data, $tag);

		$this->Tags->data = array(
			'Tag' => array(
				'id' => 'tag-1',
				'name' => 'CAKEPHP'));
		$this->Tags->admin_edit('tag-1');

		$this->assertEqual($this->Tags->redirectUrl, array('action' => 'index'));
	}
}
