<?php
/* Rating Test cases generated on: 2010-01-04 10:01:40 : 1262596420*/
App::import('Helper', array('Html', 'Tags.TagCloud'));

class TagCloudHelperTestCase extends CakeTestCase {

/**
 * Helper being tested
 * @var TagCloudHelper
 */
	public $TagCloud;

/**
 * (non-PHPdoc)
 * @see cake/tests/lib/CakeTestCase#startTest($method)
 */
	public function startTest() {
		$this->TagCloud = new TagCloudHelper();
		$this->TagCloud->Html = new HtmlHelper();
	}

/**
 * Test display method
 *
 * @return void
 */
	public function testDisplay() {
		$this->assertEqual($this->TagCloud->display(), '');
		$tags = array(
			array(
				'Tag' => array(
					'id'  => 1,
					'identifier'  => null,
					'name'  => 'CakePHP',
					'keyname'  => 'cakephp',
					'weight' => 2,
					'created'  => '2008-06-02 18:18:11',
					'modified'  => '2008-06-02 18:18:37')),
			array(
				'Tag' => array(
					'id'  => 2,
					'identifier'  => null,
					'name'  => 'CakeDC',
					'keyname'  => 'cakedc',
					'weight' => 2,
					'created'  => '2008-06-01 18:18:15',
					'modified'  => '2008-06-01 18:18:15')),
		);

		// Test tags shuffling
		$options = array(
			'shuffle' => true);
		$expected = '<a href="/search/index/by:cakephp" id="tag-1">CakePHP</a> <a href="/search/index/by:cakedc" id="tag-2">CakeDC</a> ';
		$i = 100;
		do {
			$i--;
			$result = $this->TagCloud->display($tags, $options);
		} while($result == $expected && $i > 0);
		$this->assertNotEqual($result, $expected);

		// Test normal display
		$options = array(
			'shuffle' => false);
		$result = $this->TagCloud->display($tags, $options);
		$this->assertEqual($result, $expected);

		// Test options
		$options = array_merge($options, array(
			'before' => '<span size="%size%">',
			'after' => '</span><!-- size: %size% -->',
			'maxSize' => 100,
			'minSize' => 1,
			'url' => array('controller' => 'search', 'from' => 'twitter'),
			'named' => 'query'
		));
		$result = $this->TagCloud->display($tags, $options);
		$expected = '<span size="1"><a href="/search/index/from:twitter/query:cakephp" id="tag-1">CakePHP</a> </span><!-- size: 1 -->'.
			'<span size="1"><a href="/search/index/from:twitter/query:cakedc" id="tag-2">CakeDC</a> </span><!-- size: 1 -->';
		$this->assertEqual($result, $expected);

		$tags[1]['Tag']['weight'] = 1;
		$result = $this->TagCloud->display($tags, $options);
		$expected = '<span size="100"><a href="/search/index/from:twitter/query:cakephp" id="tag-1">CakePHP</a> </span><!-- size: 100 -->'.
			'<span size="1"><a href="/search/index/from:twitter/query:cakedc" id="tag-2">CakeDC</a> </span><!-- size: 1 -->';
		$this->assertEqual($result, $expected);
	}

/**
 * (non-PHPdoc)
 * @see cake/tests/lib/CakeTestCase#endTest($method)
 */
	public function endTest() {
		unset($this->TagCloud);
		ClassRegistry::flush();
	}

}
