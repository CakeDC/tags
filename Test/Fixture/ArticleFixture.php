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

/**
 * ArticleFixture
 *
 * @package tags
 * @subpackage tags.tests.fixtures
 */
class ArticleFixture extends CakeTestFixture {

/**
 * fields property
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36),
		'title' => array('type' => 'string', 'null' => false),
		'user_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36));

/**
 * records property
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 'article-1',
			'title' => 'First Article',
			'user_id' => 'user-1'),
		array(
			'id' => 'article-2',
			'title' => 'Second Article',
			'user_id' => 'user-2'),
		array(
			'id' => 'article-3',
			'title' => 'Third Article',
			'user_id' => 'user-3'));
}
