<?php
class M49ac311a54844a9d87o822502jedc423 extends CakeMigration {
/**
 * Dependency array. Define what minimum version required for other part of db schema
 *
 * Migration defined like 'app.31' or 'plugin.PluginName.12'
 *
 * @var array $dependendOf
 * @access public
 */
	public $dependendOf = array();

/**
 * Migration array
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'tagged' => array(
					'id' => array('type'=>'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
					'foreign_key' => array('type'=>'string', 'null' => false, 'default' => NULL, 'length' => 36),
					'tag_id' => array('type'=>'string', 'null' => false, 'default' => NULL, 'length' => 36),
					'model' => array('type'=>'string', 'null' => false, 'default' => NULL, 'key' => 'index'),
					'language' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 6),
					'created' => array('type'=>'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type'=>'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'UNIQUE_TAGGING' => array('column' => array('model', 'foreign_key', 'tag_id', 'language'), 'unique' => 1),
						'INDEX_TAGGED' => array('column' => 'model', 'unique' => 0),
						'INDEX_LANGUAGE' => array('column' => 'language', 'unique' => 0))
				),
				'tags' => array(
					'id' => array('type'=>'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
					'identifier' => array('type'=>'string', 'null' => true, 'default' => NULL, 'length' => 30, 'key' => 'index'),
					'name' => array('type'=>'string', 'null' => false, 'default' => NULL, 'length' => 30),
					'keyname' => array('type'=>'string', 'null' => false, 'default' => NULL, 'length' => 30),
					'weight' => array('type' => 'integer', 'null' => false, 'default' => 0, 'length' => 2),
					'created' => array('type'=>'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type'=>'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'UNIQUE_TAG' => array('column' => array('identifier', 'keyname'), 'unique' => 1))
				),
			),
		),
		'down' => array(
			'drop_table' => array('tagged', 'tags'),
		)
	);

/**
 * before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @access public
 */
	public function before($direction) {
		return true;
	}

/**
 * after migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @access public
 */
	public function after($direction) {
		return true;
	}

}
?>
