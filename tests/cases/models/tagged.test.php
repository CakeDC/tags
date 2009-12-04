<?php
/* SVN FILE: $Id: tagged.test.php 1621 2009-11-04 17:29:33Z burzum $ */
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
				'foreign_key' => 'a12cc22a-d022-11dd-8f06-00e018bfb339',
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

}
?>