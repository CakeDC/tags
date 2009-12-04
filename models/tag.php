<?php
/* SVN FILE: $Id: tag.php 1174 2009-09-19 20:57:09Z skie $ */
/**
 * Short description for file.
 *
 * Long description for file
 *
 * PHP versions 4 and 5
 *
 * Converge Application Platform
 *
 * Copyright 2007-2008, Cake Development Corporation
 * 							1785 E. Sahara Avenue, Suite 490-423
 * 							Las Vegas, Nevada 89104
 *
 * You may obtain a copy of the License at:
 * License page: http://projects.cakedc.com/licenses/TBD  TBD
 * Copyright page: http://converge.cakedc.com/copyright/
 *
 * @filesource
 * @copyright		Copyright 2007-2008, Cake Development Corporation
 * @link				http://converge.cakedc.com/ Converge Application Platform
 * @package			converge
 * @subpackage		converge.models
 * @since			Converge v 1.0.0.0
 * @version			$Revision: 1174 $
 * @modifiedby		$LastChangedBy: skie $
 * @lastmodified	$Date: 2009-09-19 22:57:09 +0200 (Sa, 19 Sep 2009) $
 * @license			http://projects.cakedc.com/licenses/TBD  TBD
 */
/**
 * Short description for class.
 *
 * @package		converge
 * @subpackage	converge.models
 */
class Tag extends TagsAppModel {
/**
 * Name
 *
 * @var string $name
 * @access public
 */
	public $name = 'Tag';

/**
 * hasMany associations
 *
 * @var array
 * @access public
 */
	public $hasMany = array(
		'Tagged' => array(
			'className' => 'Tags.Tagged',
			'foreignKey' => 'tag_id'));

/**
 * HABTM associations
 *
 * @var array $hasAndBelongsToMany
 * @access public
 */
	public $hasAndBelongsToMany = array();

/**
 * Validation rules
 *
 * @var array
 * @access public
 */
	public $validate = array(
		'name' => array('rule' => 'notEmpty'));
}
?>