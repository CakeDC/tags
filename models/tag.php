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


/**
 * Pre-populates the tag table with entered tags
 *
 * @param array post data, should be Contoller->data
 * @return boolean
 * @access public
 */
	public function add($postData = null) {
		if (isset($postData[$this->alias]['tags'])) {
			$this->Behaviors->attach('Tags.Tagable', array(
				'resetBinding' => true,
				'automticTagging' => false));
			$this->Tag = $this;
			$result = $this->saveTags($postData[$this->alias]['tags'], false, false);
			unset($this->Tag);
			$this->Behaviors->detach('Tags.Tagable');
			return $result;
		}
	}

/**
 * Edits an existing tag, allows only to modify upper/lowercased characters
 *
 * @param string tag uuid
 * @param array controller post data usually $this->data
 * @return mixed True on successfully save else post data as array
 * @access public
 */
	public function edit($tagId = null, $postData = null) {
		$tag = $this->find('first', array(
			'contain' => array(),
			'conditions' => array(
				'Tag.id' => $tagId)));

		$this->set($tag);
		if (empty($tag)) {
			throw new Exception(__d('tags', 'Invalid Tag', true));
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
?>