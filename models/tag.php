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

/**
 * Returns the data for a single tag
 *
 * @param string keyname
 * @return array
 * @access public
 */
	public function view($keyName = null) {
		$result = $this->find('first', array(
			'conditions' => array(
				$this->alias . '.keyname' => $keyName)));

		if (empty($result)) {
			throw new Exception(__('Invalid Tag', true));
		}
		return $result;
	}

}
?>