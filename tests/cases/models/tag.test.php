<?php
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

}

?>