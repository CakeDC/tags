<?php
/* SVN FILE: $Id: tagged_fixture.php 1620 2009-11-04 17:00:18Z niles $ */
class TaggedFixture extends CakeTestFixture {
/**
 * Name
 *
 * @var string $name
 * @access public
 */
	public $name = 'Tagged';
/**
 * Table
 *
 * @var string name$table
 * @access public
 */
	public $table = 'tagged';
/**
 * Fields
 *
 * @var array $fields
 * @access public
 */
	public $fields = array(
			'id' => array('type'=>'string', 'null' => false, 'length' => 36, 'key' => 'primary'),
			'foreign_key' => array('type'=>'string', 'null' => false, 'length' => 36, 'key' => 'index'),
			'tag_id' => array('type'=>'string', 'null' => false, 'length' => 36, 'key' => 'index'),
			'model' => array('type'=>'string', 'null' => false, 'length' => 255),
			'language' => array('type' => 'string', 'length' => 6, 'default' => NULL, 'null' => true),
			'created' => array('type'=>'datetime', 'null' => true),
			'modified' => array('type'=>'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array(
				'UNIQUE_TAGGING' => array('column' => array('foreign_key', 'tag_id', 'model'), 'unique' => 1),
				'INDEX_TAGGED' => array('column' => array('model'))));
/**
 * Records
 *
 * @var array $records
 * @access public
 */
	public $records = array(
		array(
			'id' => '49357f3f-c464-461f-86ac-a85d4a35e6b6',
			'foreign_key' => 'a12cc22a-d022-11dd-8f06-00e018bfb339',
			'tag_id' => 1, //cakephp
			'model' => 'Article',
			'language' => 'eng',
			'created' => '2008-12-02 12:32:31 ',
			'modified' => '2008-12-02 12:32:31',
		),
		array(
			'id' => '49357f3f-c66c-4300-a128-a85d4a35e6b6',
			'foreign_key' => '6e2e7698-d022-11dd-b0a9-00e018bfb339',
			'tag_id' => 2, //cakedc
			'model' => 'Article',
			'language' => 'eng',
			'created' => '2008-12-02 12:32:31 ',
			'modified' => '2008-12-02 12:32:31',
		),
		array(
			'id' => '493dac81-1b78-4fa1-a761-43ef4a35e6b2',
			'foreign_key' => 2,
			'tag_id' => '49357f3f-17a0-4c42-af78-a85d4a35e6b6', //cakedc
			'model' => 'Article',
			'language' => 'eng',
			'created' => '2008-12-02 12:32:31 ',
			'modified' => '2008-12-02 12:32:31',
		),
	);
}
?>
