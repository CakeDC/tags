<?php

App::import('Core', 'Model');

class Article extends Model {
	public $useTable = 'articles';
	public $belongsTo = array();
	public $hasAndBelongsToMany = array();
	public $hasMany = array();
	public $hasOne = array();
	public $actsAs = array('Tags.Tagable');
}


class TagableTest extends CakeTestCase {
/**
 * Plugin name used for fixtures loading
 *
 * @var string
 * @access public
 */
	public $plugin = 'tags';
/**
 * Holds the instance of the model
 *
 * @var mixed $UsersAddon
 * @access public
 */
	public $Article = null;

/**
 * Fixtures associated with this test case
 *
 * @var array
 * @access public
 */
	public $fixtures = array(
		'plugin.tags.tagged',
		'plugin.tags.tag',
		'plugin.tags.article');

/**
 * Method executed before each test
 *
 * @access public
 */
	public function startTest() {
		$this->Article = ClassRegistry::init('Article');
		$this->Article->Behaviors->attach('Tags.Tagable', array());
	}

/**
 * Method executed after each test
 *
 * @access public
 */
	public function endTest() {
		unset($this->Article);
		ClassRegistry::flush();
	}

/**
 * Testings saving of tags trough the specified field in the tagable model
 *
 * @access public
 */
	public function testTagSaving() {
		$data['id'] = 1;
		$data['tags'] = 'foo, bar, test';
		$this->Article->save($data, false);
		$result = $this->Article->find('first', array(
			'conditions' => array(
			'id' => 1)));
		$this->assertTrue(!empty($result['Article']['tags']));

		$data['tags'] = 'foo, developer, developer, php';
		$this->Article->save($data, false);
		$result = $this->Article->find('first', array(
			'contain' => array('Tag'),
			'conditions' => array(
				'id' => 1)));
		$this->assertTrue(!empty($result['Article']['tags']));
	}

/**
 * Testings Tagable::tagArrayToString()
 *
 * @access public
 */
	public function testTagArrayToString() {
		$data['id'] = 1;
		$data['tags'] = 'foo, bar, test';
		$this->Article->save($data, false);
		$result = $this->Article->find('first', array(
			'conditions' => array(
				'id' => 1)));
		$result = $this->Article->tagArrayToString($result['Tag']);
		$this->assertTrue(!empty($result));
		$this->assertIsA($result, 'string');
	}

/**
 * Testings Tagable::multibyteKey()
 *
 * @access public
 */
	public function testMultibyteKey() {
		$result = $this->Article->multibyteKey('this is _ a Nice ! - _ key!');
		$this->assertEqual('thisisanicekey', $result);

		$result = $this->Article->multibyteKey('Äü-Ü_ß');
		$this->assertEqual('äüüß', $result);
	}

}

?>