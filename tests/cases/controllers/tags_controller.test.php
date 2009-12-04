<?php
/* SVN FILE: $Id: tags_controller.test.php 991 2009-08-24 13:06:34Z burzum $ */
App::import('Controller', 'Tags');

class TestTags extends TagsController {
	var $autoRender = false;
}

class TagsControllerTest extends CakeTestCase {
	var $Tags = null;

	function setUp() {
		$this->Tags = new TestTags();
	}

	function testTagsControllerInstance() {
		$this->assertTrue(is_a($this->Tags, 'TagsController'));
	}

	function tearDown() {
		unset($this->Tags);
	}
}
?>
