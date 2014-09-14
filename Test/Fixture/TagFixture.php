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

/**
 * TagFixture
 *
 * @package tags
 * @subpackage tags.tests.fixtures
 */
class TagFixture extends CakeTestFixture {

/**
 * Table
 *
 * @var string $table
 */
	public $table = 'tags';

/**
 * Fields
 *
 * @var array $fields
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary'),
		'identifier' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 30, 'key' => 'index'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 30),
		'keyname' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 30),
		'occurrence' => array('type' => 'integer', 'null' => false, 'default' => 0, 'length' => 8),
		'article_occurrence' => array('type' => 'integer', 'null' => false, 'default' => 0, 'length' => 8),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'UNIQUE_TAG' => array('column' => array('identifier', 'keyname'), 'unique' => 1)
		)
	);

/**
 * Records
 *
 * @var array $records
 */
	public $records = array(
		array(
			'id' => 'tag-1',
			'identifier' => null,
			'name' => 'CakePHP',
			'keyname' => 'cakephp',
			'occurrence' => 1,
			'article_occurrence' => 1,
			'created' => '2008-06-02 18:18:11',
			'modified' => '2008-06-02 18:18:37'),
		array(
			'id' => 'tag-2',
			'identifier' => null,
			'name' => 'CakeDC',
			'keyname' => 'cakedc',
			'occurrence' => 1,
			'article_occurrence' => 1,
			'created' => '2008-06-01 18:18:15',
			'modified' => '2008-06-01 18:18:15'),
		array(
			'id' => 'tag-3',
			'identifier' => null,
			'name' => 'CakeDC',
			'keyname' => 'cakedc',
			'occurrence' => 1,
			'article_occurrence' => 1,
			'created' => '2008-06-01 18:18:15',
			'modified' => '2008-06-01 18:18:15'
		)
	);

}
