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

App::import('Controller', 'Tags.Tags');
App::import('Model', 'Tags.Tag');

class TestTagsController extends TagsController {
/**
 * Auto render
 *
 * @var boolean
 * @access public
 */
	public $autoRender = false;

/**
 * Redirect URL
 *
 * @var mixed
 * @access public
 */
	public $redirectUrl = null;

/**
 * Override controller method for testing
 */
	public function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}

/**
 * Override controller method for testing
 */
	public function render($action = null, $layout = null, $file = null) {
		$this->renderedView = $action;
	}
}

class TagsControllerTest extends CakeTestCase {

/**
 *
 */
	public $fixtures = array(
		'plugin.tags.tagged',
		'plugin.tags.tag');
/**
 * Tags Controller Instance
 *
 * @return void
 * @access public
 */
	public $Tags = null;

/**
 * startTest
 *
 * @return void
 * @access public
 */
	public function startTest() {
		$this->Tags = new TestTagsController();
		$this->Tags->params = array(
			'named' => array(),
			'url' => array());
		$this->Tags->constructClasses();
	}

/**
 * endTest
 *
 * @return void
 * @access public
 */
	public function endTest() {
		unset($this->Tags);
	}

/**
 * testTagsControllerInstance
 *
 * @return void
 * @access public
 */
	public function testTagsControllerInstance() {
		$this->assertTrue(is_a($this->Tags, 'TagsController'));
	}

/**
 * testIndex
 *
 * @return void
 * @access public
 */
	public function testIndex() {
		$this->Tags->index();
		$this->assertTrue(!empty($this->Tags->viewVars['tags']));
	}

/**
 * testIndex
 *
 * @return void
 * @access public
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
 * @access public
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
 * @access public
 */
	public function testAdminIndex() {
		$this->Tags->admin_index();
		$this->assertTrue(!empty($this->Tags->viewVars['tags']));
	}

/**
 * testAdminDelete
 *
 * @return void
 * @access public
 */
	public function testAdminDelete() {
		$this->Tags->admin_delete('WRONG-ID');
		$this->assertEqual($this->Tags->redirectUrl, array('action' => 'index'));
		$this->assertEqual($_SESSION['Message']['flash']['message'], 'Invalid Tag.');

		$this->Tags->admin_delete(1);
		$this->assertEqual($this->Tags->redirectUrl, array('action' => 'index'));
		$this->assertEqual($_SESSION['Message']['flash']['message'], 'Tag deleted.');
	}

/**
 * testAdminAdd
 *
 * @return void
 * @access public
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
 * @access public
 */
	public function testAdminEdit() {
		$this->Tags->admin_edit(1);
		$tag = array(
			'Tag' => array(
				'id'  => 1,
				'identifier'  => null,
				'name'  => 'CakePHP',
				'keyname'  => 'cakephp',
				'weight' => 2,
				'created'  => '2008-06-02 18:18:11',
				'modified'  => '2008-06-02 18:18:37',
				'tags' => null));
		$this->assertEqual($this->Tags->data, $tag);

		$this->Tags->data = array(
			'Tag' => array(
				'id' => 1,
				'name' => 'CAKEPHP'));
		$this->Tags->admin_edit(1);
		$this->assertEqual($this->Tags->redirectUrl, array('action' => 'index'));
	}
}
?>