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

App::uses('Tagged', 'Tags.Model');
App::uses('Model', 'Model');

/**
 * TagggedArticle Test Model
 */
class TaggedArticle extends Model {
	public $useTable = 'articles';
	public $actsAs = array(
		'Tags.Taggable');
}

/**
 * Short description for class.
 *
 * @package tags
 * @subpackage tags.tests.cases.models
 */
class TaggedTest extends CakeTestCase {

/**
 * Tagged model
 *
 * @var Tagged
 */
	public $Tagged = null;

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.tags.tagged',
		'plugin.tags.tag',
		'plugin.tags.article',
		'plugin.tags.user');

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Tagged = ClassRegistry::init('Tags.Tagged');
	}

/**
 * tearDown
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->Tagged);
		ClassRegistry::flush(); 
	}

/**
 * testTaggedInstance
 *
 * @return void
 */
	public function testTaggedInstance() {
		$this->assertTrue(is_a($this->Tagged, 'Tagged'));
	}

/**
 * testTaggedInstance
 *
 * @return void
 */
	public function testTaggedFind() {
		$this->Tagged->recursive = -1;
		$result = $this->Tagged->find('first');
		$this->assertTrue(!empty($result));

		$expected = array(
			'Tagged' => array(
				'id' => '49357f3f-c464-461f-86ac-a85d4a35e6b6',
				'foreign_key' => 'article-1',
				'tag_id' => 'tag-1', //cakephp
				'model' => 'Article',
				'language' => 'eng',
				'times_tagged' => 1,
				'created' => '2008-12-02 12:32:31',
				'modified' => '2008-12-02 12:32:31'));

		$this->assertEqual($result, $expected);
	}

/**
 * testFindCloud
 *
 * @return void
 */
	public function testFindCloud() {
		$result = $this->Tagged->find('cloud', array(
			'model' => 'Article'));
		
		$this->assertEqual(count($result), 3);
		$this->assertTrue(isset($result[0][0]['occurrence']));
		$this->assertEqual($result[0][0]['occurrence'], 1);

		$result = $this->Tagged->find('cloud');
		$this->assertTrue(is_array($result) && !empty($result));

		$result = $this->Tagged->find('cloud', array(
			'limit' => 1));
		$this->assertEqual(count($result), 1);
	}

/**
 * Test custom _findTagged method
 * 
 * @return void
 */
	public function testFindTagged() {
		$this->Tagged->recursive = -1;
		$result = $this->Tagged->find('tagged', array(
			'by' => 'cakephp',
			'model' => 'Article'));
		$this->assertEqual(count($result), 1);
		$this->assertEqual($result[0]['Article']['id'], 'article-1');

		$result = $this->Tagged->find('tagged', array(
			'model' => 'Article'));
		$this->assertEqual(count($result), 2);

		// Test call to paginateCount by Controller::pagination()
		$result = $this->Tagged->paginateCount(array(), 1, array(
			'model' => 'Article',
			'type' => 'tagged'));
		$this->assertEqual($result, 2);
	}

/**
 * Test custom _findTagged method with additional conditions on the model
 *
 * @return void
 */
	public function testFindTaggedWithConditions() {
		$this->Tagged->recursive = -1;
		$result = $this->Tagged->find('tagged', array(
			'by' => 'cakephp',
			'model' => 'Article',
			'conditions' => array('Article.title LIKE' => 'Second %')));
		$this->assertEqual(count($result), 0);

		$result = $this->Tagged->find('tagged', array(
			'by' => 'cakephp',
			'model' => 'Article',
			'conditions' => array('Article.title LIKE' => 'First %')));
		$this->assertEqual(count($result), 1);
		$this->assertEqual($result[0]['Article']['id'], 'article-1');
	}

/**
 * testDeepAssociations
 *
 * @link https://github.com/CakeDC/tags/issues/15
 * @return void
 */
	public function testDeepAssociationsHasOne() {
		$this->Tagged->bindModel(array(
			'belongsTo' => array(
				'Article' => array(
					'className' => 'TaggedArticle',
					'foreignKey' => 'foreign_key'))));

		$this->Tagged->Article->bindModel(array(
			'hasOne' => array(
				'User' => array())));

		$result = $this->Tagged->find('all', array(
			'contain' => array(
				'Article' => array(
					'User'))));

		$this->assertEqual($result[0]['Article']['User']['name'], 'CakePHP');
	}

/**
 * testDeepAssociationsBelongsTo
 *
 * @link https://github.com/CakeDC/tags/issues/15
 * @return void
 */
	public function testDeepAssociationsBelongsTo() {
		$this->Tagged->bindModel(array(
			'belongsTo' => array(
				'Article' => array(
					'className' => 'TaggedArticle',
					'foreignKey' => 'foreign_key'))));
	
		$this->Tagged->Article->bindModel(array(
			'belongsTo' => array(
				'User' => array())));
	
		$result = $this->Tagged->find('all', array(
			'contain' => array(
				'Article' => array(
				'User'))));

		$this->assertEqual($result[0]['Article']['User']['name'], 'CakePHP');
	}

}
