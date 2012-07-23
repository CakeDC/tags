<?php
/**
 * Copyright 2009-2010, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2009-2010, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * ArticleFixture
 *
 * @package tags
 * @subpackage tags.tests.fixtures
 */
class ArticleFixture extends CakeTestFixture {

/**
 * name property
 *
 * @var string 'AnotherArticle'
 */
	public $name = 'Article';

/**
 * fields property
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36),
		'title' => array('type' => 'string', 'null' => false));

/**
 * records property
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 'article-1',
			'title' => 'First Article'),
		array(
			'id' => 'article-2',
			'title' => 'Second Article'),
		array(
			'id' => 'article-3',
			'title' => 'Third Article'));
}
