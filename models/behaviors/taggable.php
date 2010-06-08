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
 * @subpackage	plugins.tags.models.behaviors
 */

class TaggableBehavior extends ModelBehavior {

/**
 * Settings array
 *
 * @var array
 * @access public
 */
	public $settings = array();

/**
 * Default settings
 *
 * separator				- separator used to enter a lot of tags, comma by default
 * tagAlias					- model alias for Tag model
 * tagClass					- class name of the table storing the tags
 * taggedClass				- class name of the HABTM association table between tags and models
 * field					- the fieldname that contains the raw tags as string
 * foreignKey				- foreignKey used in the HABTM association
 * associationForeignKey	- associationForeignKey used in the HABTM association
 * automaticTagging			- if set to true you don't need to use saveTags() manually
 * language					- only tags in a certain language, string or array
 *
 * @var array
 * @access protected
 */
	protected $_defaults = array(
		'separator' => ',',
		'field' => 'tags',
		'tagAlias' => 'Tag',
		'tagClass' => 'Tags.Tag',
		'taggedClass' => 'Tags.Tagged',
		'foreignKey' => 'foreign_key',
		'associationForeignKey' => 'tag_id',
		'cacheWeight' => true,
		'automaticTagging' => true,
		'unsetInAfterFind' => false,
		'resetBinding' => false,
		'taggedCounter' => false);

/**
 * Setup
 *
 * @param AppModel $Model
 * @param array $settings
 * @access public
 */
	public function setup(Model $Model, $settings = array()) {
		if (!isset($this->settings[$Model->alias])) {
			$this->settings[$Model->alias] = $this->_defaults;
		}

		$this->settings[$Model->alias] = array_merge($this->settings[$Model->alias], $settings);
		$this->settings[$Model->alias]['withModel'] = $this->settings[$Model->alias]['taggedClass'];

		$Model->bindModel(array('hasAndBelongsToMany' => array(
			'Tag' => array(
				'className' => $this->settings[$Model->alias]['tagClass'],
				'foreignKey' => $this->settings[$Model->alias]['foreignKey'],
				'associationForeignKey' => $this->settings[$Model->alias]['associationForeignKey'],
				'unique' => true,
				'conditions' => array(
					'Tagged.model' => $Model->name),
				'fields' => '',
				'dependent' => true,
				'with' => $this->settings[$Model->alias]['withModel']))),  $this->settings[$Model->alias]['resetBinding']);
	}

/**
 * Saves a string of tags
 *
 * @param AppModel $Model
 * @param string $string comma separeted list of tgas to be saved
 * @param mixed $foreignKey the identifier for the record to associate the tags with
 * @param boolean $update true will remove tags that are not in the $string, false wont
 * do this and just add new tags without removing existing tags associated to
 * the current set foreign key
 * @return array
 * @access public
 */
	public function saveTags(Model $Model, $string = null, $foreignKey = null, $update = true) {
		if (is_string($string) && !empty($string) && (!empty($foreignKey) || $foreignKey === false)) {
			$tagClass = $this->settings[$Model->alias]['tagAlias'];
			$tagModel = $Model->Tag;
			$array = explode($this->settings[$Model->alias]['separator'], $string);

			$tags = $identifiers = array();
			foreach ($array as $tag) {
				$identifier = null;
				if (strpos($tag, ':') !== false) {
					$t = explode(':', $tag);
					$identifier = trim($t[0]);
					$tag = $t[1];
				}
				$tag = trim($tag);
				if (!empty($tag)) {
					$key = $this->multibyteKey($Model, $tag);
					if (empty($tags[$key])) {
						$tags[] = array('name' => $tag, 'identifier' => $identifier, 'keyname' => $key);
						$identifiers[$key][] = $identifier;
					}
				}
			}

			if (!empty($tags)) {
				$existingTags = $tagModel->find('all', array(
					'contain' => array(),
					'conditions' => array(
						'Tag.keyname' => Set::extract($tags, '{n}.keyname')),
					'fields' => array(
						'Tag.identifier',
						'Tag.keyname',
						'Tag.name',
						'Tag.id')));

				if (!empty($existingTags)) {
					foreach ($existingTags as $existing) {
						$existingTagKeyNames[] = $existing['Tag']['keyname'];
						$existingTagIds[] = $existing['Tag']['id'];
						$existingTagIdentifiers[$existing['Tag']['keyname']][] = $existing['Tag']['identifier'];
					}
					$newTags = array();
					foreach($tags as $possibleNewTag) {
						$key = $possibleNewTag['keyname'];
						if (!in_array($key, $existingTagKeyNames)) {
							array_push($newTags, $possibleNewTag);
						} elseif (!empty($identifiers[$key])) {
							$newIdentifiers = array_diff($identifiers[$key], $existingTagIdentifiers[$key]);
							foreach ($newIdentifiers as $identifier) {
								array_push($newTags, array_merge($possibleNewTag, compact('identifier')));
							}
							unset($identifiers[$key]);
						}
					}
				} else {
					$existingTagIds = $alreadyTagged = array();
					$newTags = $tags;
				}

				foreach ($newTags as $key => $newTag) {
					$tagModel->create();
					$tagModel->save($newTag);
					$newTagIds[] = $tagModel->id;
				}

				if ($foreignKey !== false) {
					if (!empty($newTagIds)) {
						$existingTagIds = array_merge($existingTagIds, $newTagIds);
					}

					$tagged = $tagModel->Tagged->find('all', array(
						'contain' => array(),
						'conditions' => array(
							'Tagged.model' => $Model->name,
							'Tagged.foreign_key' => $foreignKey,
							'Tagged.language' => Configure::read('Config.language'),
							'Tagged.tag_id' => $existingTagIds),
						'fields' => 'Tagged.tag_id'));

					$deleteAll = array(
						'Tagged.foreign_key' => $foreignKey,
						'Tagged.model' => $Model->name);

					if (!empty($tagged)) {
						$alreadyTagged = Set::extract($tagged, '{n}.Tagged.tag_id');
						$existingTagIds = array_diff($existingTagIds, $alreadyTagged);

						$deleteAll['NOT'] = array('Tagged.tag_id' => $alreadyTagged);
					}

					if ($update == true) {
						$tagModel->Tagged->deleteAll($deleteAll, false);
					} elseif ($this->settings[$Model->alias]['taggedCounter'] && !empty($alreadyTagged)) {
						$tagModel->Tagged->updateAll(array('times_tagged' => 'times_tagged + 1'), array('Tagged.tag_id' => $alreadyTagged));
					}

					foreach ($existingTagIds as $tagId) {
						$data['Tagged']['tag_id'] = $tagId;
						$data['Tagged']['model'] = $Model->name;
						$data['Tagged']['foreign_key'] = $foreignKey;
						$data['Tagged']['language'] = Configure::read('Config.language');
						$tagModel->Tagged->create($data);
						$tagModel->Tagged->save();
					}
				}
			}
			return true;
		}
		return false;
	}

/**
 * Creates a multibyte safe unique key
 *
 * @param object Model instance
 * @param string Tag name string
 * @return string Multibyte safe key string
 * @access public
 */
	public function multibyteKey(Model $Model, $string = null) {
		$str = mb_strtolower($string);
		$str = preg_replace('/\xE3\x80\x80/', ' ', $str);
		$str = str_replace(array('_', '-'), '', $str);
		$str = preg_replace( '#[:\#\*"()~$^{}`@+=;,<>!&%\.\]\/\'\\\\|\[]#', "\x20", $str );
		$str = str_replace('?', '', $str);
		$str = trim($str);
		$str = preg_replace('#\x20+#', '', $str);
		return $str;
	}

/**
 * Generates comma-delimited string of tag names from tag array(), needed for
 * initialization of data for text input
 *
 * Example usage (only 'Tag.name' field is needed inside of method):
 * <code>
 * $this->Blog->hasAndBelongsToMany['Tag']['fields'] = array('name', 'keyname');
 * $blog = $this->Blog->read(null, 123);
 * $blog['Blog']['tags'] = $this->Blog->Tag->tagArrayToString($blog['Tag']);
 * </code>
 *
 * @param array $string
 * @return string
 * @access public
 */
	public function tagArrayToString(Model $Model, $data = null) {
		if ($data) {
			return join($this->settings[$Model->alias]['separator'].' ', Set::extract($data, '{n}.name'));
		}
		return '';
	}

/**
 * afterSave callback
 *
 * @param AppModel $Model
 */
	public function afterSave(Model $Model) {
		if ($this->settings[$Model->alias]['automaticTagging'] == true && !empty($Model->data[$Model->alias][$this->settings[$Model->alias]['field']])) {
			$this->saveTags($Model, $Model->data[$Model->alias][$this->settings[$Model->alias]['field']], $Model->id);
		}
	}

/**
 * beforeSave callback
 *
 * @param AppModel $Model
 * @param array $results
 * @param mixed
 * @return array
 */
	public function afterFind(Model $Model, $results, $primary) {
		extract($this->settings[$Model->alias]);
		foreach ($results as $key => $row) {
			$row[$Model->alias][$field] = '';
			if (isset($row[$tagAlias]) && !empty($row[$tagAlias])) {
				$row[$Model->alias][$field] = $this->tagArrayToString($Model, $row[$tagAlias]);
				if ($unsetInAfterFind == true) {
					unset($row[$tagAlias]);
				}
			}
			$results[$key] = $row;
		}
		return $results;
	}
}
?>