<?php
/**
 * CakePHP Tags Plugin
 *
 * Copyright 2009 - 2010, Cake Development Corporation
 *                        1785 E. Sahara Avenue, Suite 490-423
 *                        Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright 2009 - 2010, Cake Development Corporation (http://cakedc.com)
 * @link      http://github.com/CakeDC/Tags
 * @package   plugins.tags
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Short description for class.
 *
 * @package		plugins.tags
 * @subpackage	plugins.tags.config.migrations
 */
class M8d01880f01c11e0be500800200c9a66 extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'Adds the column times_tagged to track the number of times a record has been tagged';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'tags' => array(
					'occurrence' => array('type' => 'integer', 'null' => false, 'default' => 0, 'length' => 8),
					)
				),
			'drop_field' => array(
				'tags' => array('weight')
			),
		),
		'down' => array(
			'drop_field' => array(
				'tags' => array('occurrence')
			),
			'create_field' => array(
				'tags' => array(
					'weight' => array('type' => 'integer', 'null' => false, 'default' => 0, 'length' => 2),
				),
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function after($direction) {
		return true;
	}
}
?>