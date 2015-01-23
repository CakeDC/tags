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
 * @package         plugins.tags
 * @subpackage  plugins.tags.config.migrations
 */

class M49ac311a54844a9d87o822502jedc423 extends CakeMigration
{

/**
 * Migration description
 *
 * @var string
 * @access public
 */
    public $description = 'Initialize Tags Schema';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
    public $migration = array(
        'up' => array(
            'create_table' => array(
                'tagged' => array(
                    'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary'),
                    'foreign_key' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36),
                    'tag_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36),
                    'model' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index'),
                    'language' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 6),
                    'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
                    'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
                    'indexes' => array(
                        'PRIMARY' => array('column' => 'id', 'unique' => 1),
                        'UNIQUE_TAGGING' => array('column' => array('model', 'foreign_key', 'tag_id', 'language'), 'unique' => 1),
                        'INDEX_TAGGED' => array('column' => 'model', 'unique' => 0),
                        'INDEX_LANGUAGE' => array('column' => 'language', 'unique' => 0)
                    )
                ),
                'tags' => array(
                    'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary'),
                    'identifier' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 30, 'key' => 'index'),
                    'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 30),
                    'keyname' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 30),
                    'weight' => array('type' => 'integer', 'null' => false, 'default' => 0, 'length' => 2),
                    'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
                    'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
                    'indexes' => array(
                        'PRIMARY' => array('column' => 'id', 'unique' => 1),
                        'UNIQUE_TAG' => array('column' => array('identifier', 'keyname'), 'unique' => 1)
                    )
                )
            )
        ),
        'down' => array(
            'drop_table' => array('tagged', 'tags')
        )
    );

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
    public function before($direction)
    {
        return true;
    }

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
    public function after($direction)
    {
        return true;
    }
}
