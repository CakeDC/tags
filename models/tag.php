<?php
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
		'name' => array('rule' => 'notEmpty'),
		'keyname' => array('rule' => 'notEmpty'));
}
?>