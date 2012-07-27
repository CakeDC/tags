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
 * Tag model
 *
 * @package tags
 * @subpackage tags.models
 */
class Tag extends TagsAppModel {

/**
 * Name
 *
 * @var string $name
 */
	public $name = 'Tag';

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Tagged' => array(
			'className' => 'Tags.Tagged',
			'foreignKey' => 'tag_id'));

/**
 * HABTM associations
 *
 * @var array $hasAndBelongsToMany
 */
	public $hasAndBelongsToMany = array();

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array('rule' => 'notEmpty'),
		'keyname' => array('rule' => 'notEmpty'));

/**
 * Returns the data for a single tag
 *
 * @param string keyname
 * @return array
 */
	public function view($keyName = null) {
		$result = $this->find('first', array(
			'conditions' => array(
				$this->alias . '.keyname' => $keyName)));

		if (empty($result)) {
			throw new Exception(__d('tags', 'Invalid Tag.', true));
		}
		return $result;
	}


/**
 * Pre-populates the tag table with entered tags
 *
 * @param array post data, should be Contoller->data
 * @return boolean
 */
	public function add($postData = null) {
		if (isset($postData[$this->alias]['tags'])) {
			$this->Behaviors->attach('Tags.Taggable', array(
				'resetBinding' => true,
				'automaticTagging' => false));
			$this->Tag = $this;
			$result = $this->saveTags($postData[$this->alias]['tags'], false, false);
			unset($this->Tag);
			$this->Behaviors->detach('Tags.Taggable');
			return $result;
		}
	}

/**
 * Edits an existing tag, allows only to modify upper/lowercased characters
 *
 * @param string tag uuid
 * @param array controller post data usually $this->data
 * @return mixed True on successfully save else post data as array
 */
	public function edit($tagId = null, $postData = null) {
		$tag = $this->find('first', array(
			'contain' => array(),
			'conditions' => array(
				$this->alias . '.' . $this->primaryKey => $tagId)));

		$this->set($tag);
		if (empty($tag)) {
			throw new Exception(__d('tags', 'Invalid Tag.', true));
		}

		if (!empty($postData[$this->alias]['name'])) {
			if (strcasecmp($tag['Tag']['name'], $postData[$this->alias]['name']) !== 0) {
				return false;
			}
			$this->set($postData);
			$result = $this->save(null, true);
			if ($result) {
				$this->data = $result;
				return true;
			} else {
				return $postData;
			}
		}
	}
}
