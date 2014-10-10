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

App::uses('View', 'View');
App::uses('HtmlHelper', 'View/Helper');
App::uses('TagCloudHelper', 'Tags.View/Helper');

/**
 * TagCloudHelperTest
 *
 * @package tags
 * @subpackage tags.tests.cases.helpers
 */
class TagCloudHelperTest extends CakeTestCase {

/**
 * Sample data for testing purposes
 *
 * @var array
 */
	public $sampleTags = array(
		array(
			'Tag' => array(
				'id' => 1,
				'identifier' => null,
				'name' => 'CakePHP',
				'keyname' => 'cakephp',
				'weight' => 2,
				'created' => '2008-06-02 18:18:11',
				'modified' => '2008-06-02 18:18:37')),
		array(
			'Tag' => array(
				'id' => 2,
				'identifier' => null,
				'name' => 'CakeDC',
				'keyname' => 'cakedc',
				'weight' => 2,
				'created' => '2008-06-01 18:18:15',
				'modified' => '2008-06-01 18:18:15')),
	);

/**
 * Helper being tested
 *
 * @var TagCloudHelper
 */
	public $TagCloud;

/**
 * (non-PHPdoc)
 * @see cake/tests/lib/CakeTestCase#setUp($method)
 */
	public function setUp() {
		parent::setUp();
		$controller = null;
		$this->View = new View($controller);
		$this->TagCloud = new TagCloudHelper($this->View);
		$this->TagCloud->Html = new HtmlHelper($this->View);
	}

/**
 * Test display method
 *
 * @return void
 */
	public function testDisplay() {
		$this->assertEquals($this->TagCloud->display(), '');

		// Test tags shuffling
		$options = array(
			'shuffle' => true);
		$expected = '<a href="/search/index/by:cakephp" id="tag-1">CakePHP</a> <a href="/search/index/by:cakedc" id="tag-2">CakeDC</a> ';
		$i = 100;
		do {
			$i--;
			$result = $this->TagCloud->display($this->sampleTags, $options);
		} while ($result == $expected && $i > 0);
		$this->assertNotEqual($result, $expected);

		// Test normal display
		$options = array(
			'shuffle' => false);
		$result = $this->TagCloud->display($this->sampleTags, $options);
		$this->assertEquals($result, $expected);

		// Test options
		$options = array_merge($options, array(
			'before' => '<span size="%size%">',
			'after' => '</span><!-- size: %size% -->',
			'maxSize' => 100,
			'minSize' => 1,
			'url' => array('controller' => 'search', 'from' => 'twitter'),
			'named' => 'query'
		));
		$result = $this->TagCloud->display($this->sampleTags, $options);
		$expected = '<span size="1"><a href="/search/index/from:twitter/query:cakephp" id="tag-1">CakePHP</a> </span><!-- size: 1 -->'.
			'<span size="1"><a href="/search/index/from:twitter/query:cakedc" id="tag-2">CakeDC</a> </span><!-- size: 1 -->';
		$this->assertEquals($result, $expected);

		$tags = $this->sampleTags;
		$tags[1]['Tag']['weight'] = 1;
		$result = $this->TagCloud->display($tags, $options);
		$expected = '<span size="100"><a href="/search/index/from:twitter/query:cakephp" id="tag-1">CakePHP</a> </span><!-- size: 100 -->'.
			'<span size="1"><a href="/search/index/from:twitter/query:cakedc" id="tag-2">CakeDC</a> </span><!-- size: 1 -->';
		$this->assertEquals($result, $expected);
	}

	public function testDisplayShouldDefineCorrectSizeWhenCustomWeightField() {
		$tags = $this->sampleTags;
		$tags[0]['Tag']['custom_weight'] = 6;
		$tags[1]['Tag']['custom_weight'] = 3;

		$options = array(
			'before' => '<!-- size: %size% -->',
			'shuffle' => false,
			'extract' => '{n}.Tag.custom_weight',
		);

		$result = $this->TagCloud->display($tags, $options);
		$expected = '<!-- size: 160 --><a href="/search/index/by:cakephp" id="tag-1">CakePHP</a> '.
			'<!-- size: 80 --><a href="/search/index/by:cakedc" id="tag-2">CakeDC</a> ';
		$this->assertEquals($result, $expected);
	}

/**
 * Test query string param type
 */
	public function testQueryStringUrlParams() {
		$tags = $this->sampleTags;
		$tags[0]['Tag']['custom_weight'] = 6;
		$tags[1]['Tag']['custom_weight'] = 3;

		$options = array(
			'shuffle' => false,
			'extract' => '{n}.Tag.custom_weight',
			'paramType' => 'querystring'
		);

		$result = $this->TagCloud->display($tags, $options);
		$expected = '<a href="/search?by=cakephp" id="tag-1">CakePHP</a> '.
			'<a href="/search?by=cakedc" id="tag-2">CakeDC</a> ';
		$this->assertEquals($result, $expected);
	}

/**
 * (non-PHPdoc)
 * @see cake/tests/lib/CakeTestCase#tearDown($method)
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->TagCloud, $this->View);
		ClassRegistry::flush();
	}
}
