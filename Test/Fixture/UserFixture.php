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
 * UserFixture
 *
 * @package tags
 * @subpackage tags.tests.fixtures
 */
class UserFixture extends CakeTestFixture {

/**
 * fields property
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36),
		'name' => array('type' => 'string', 'null' => false),
		'article_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36));

/**
 * records property
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 'user-1',
			'name' => 'CakePHP',
			'article_id' => 'article-1'),
		array(
			'id' => 'user-2',
			'name' => 'Second User',
			'article_id' => 'article-2'),
		array(
			'id' => 'user-3',
			'name' => 'Third User',
			'article_id' => 'article-3'));

}
