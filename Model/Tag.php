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
App::uses('TagsAppModel', 'Tags.Model');

/**
 * Tag model
 *
 * @package tags
 * @subpackage tags.models
 */
class Tag extends TagsAppModel {

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Tagged' => array(
			'className' => 'Tags.Tagged',
			'foreignKey' => 'tag_id'
		)
	);

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
		'name' => array('rule' => 'notBlank'),
		'keyname' => array('rule' => 'notBlank')
	);

/**
 * Returns the data for a single tag
 *
 * @param string $keyName Tag key name
 * @throws CakeException
 * @return array
 */
	public function view($keyName = null) {
		$result = $this->find('first', array(
			'conditions' => array(
				$this->alias . '.keyname' => $keyName
			)
		));

		if (empty($result)) {
			throw new CakeException(__d('tags', 'Invalid Tag.'));
		}
		return $result;
	}

/**
 * Pre-populates the tag table with entered tags
 *
 * @param array $postData Post data, should be Contoller->data
 * @return bool
 */
	public function add($postData = null) {
		if (isset($postData[$this->alias]['tags'])) {
			$this->Behaviors->load('Tags.Taggable', array(
				'resetBinding' => true,
				'automaticTagging' => false
			));
			$this->Tag = $this;
			$result = $this->saveTags($postData[$this->alias]['tags'], false, false);
			unset($this->Tag);
			$this->Behaviors->unload('Tags.Taggable');
			return $result;
		}
	}

/**
 * Edits an existing tag, allows only to modify upper/lowercased characters
 *
 * @param string $tagId Tag UUID
 * @param array $postData Controller post data usually $this->request->data
 * @throws CakeException
 * @return mixed True on successfully save else post data as array
 */
	public function edit($tagId = null, $postData = null) {
		$tag = $this->find('first', array(
			'contain' => array(),
			'conditions' => array(
				$this->alias . '.' . $this->primaryKey => $tagId
			)
		));

		$this->set($tag);
		if (empty($tag)) {
			throw new CakeException(__d('tags', 'Invalid Tag.'));
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
