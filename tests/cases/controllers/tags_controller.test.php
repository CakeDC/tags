<?php
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
	public public function redirect($url, $status = null, $exit = true) {
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
 * testAdminIndex
 *
 * @return void
 * @access public
 */
	public function testAdminDelete() {
		$this->assertFalse($this->Tags->admin_delete('WRONG-ID'));
		$this->assertEqual($this->Tags->redirectUrl, array('action' => 'index'));


		$this->assertTrue($this->Tags->admin_delete(1));
		$this->assertEqual($this->Tags->redirectUrl, array('action' => 'index'));
	}

}

?>