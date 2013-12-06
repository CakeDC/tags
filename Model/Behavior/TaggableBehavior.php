<?php
/**
 * Copyright 2009-2013, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2009-2013, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('ModelBehavior', 'Model');

/**
 * Taggable Behavior
 *
 * @package tags
 * @subpackage tags.models.behaviors
 */
class TaggableBehavior extends ModelBehavior {

/**
 * Settings array
 *
 * @var array
 */
	public $settings = array();

/**
 * Default settings
 *
 * separator             	- separator used to enter a lot of tags, comma by default
 * tagAlias              	- model alias for Tag model
 * tagClass              	- class name of the table storing the tags
 * taggedClass           	- class name of the HABTM association table between tags and models
 * field                 	- the fieldname that contains the raw tags as string
 * foreignKey            	- foreignKey used in the HABTM association
 * associationForeignKey 	- associationForeignKey used in the HABTM association
 * automaticTagging      	- if set to true you don't need to use saveTags() manually
 * language              	- only tags in a certain language, string or array
 * taggedCounter         	- true to update the number of times a particular tag was used for a specific record
 * unsetInAfterFind      	- unset 'Tag' results in afterFind
 * deleteTagsOnEmptyField 	- delete associated Tags if field is empty.
 *
 * @var array
 */
	protected $_defaults = array(
		'separator' => ',',
		'field' => 'tags',
		'tagAlias' => 'Tag',
		'tagClass' => 'Tags.Tag',
		'taggedAlias' => 'Tagged',
		'taggedClass' => 'Tags.Tagged',
		'foreignKey' => 'foreign_key',
		'associationForeignKey' => 'tag_id',
		'cacheOccurrence' => true,
		'automaticTagging' => true,
		'unsetInAfterFind' => false,
		'resetBinding' => false,
		'taggedCounter' => false,
		'deleteTagsOnEmptyField' => false
	);

/**
 * Setup
 *
 * @param AppModel $model
 * @param array $settings
 */
	public function setup(Model $model, $config = array()) {
		if (!isset($this->settings[$model->alias])) {
			$this->settings[$model->alias] = $this->_defaults;
		}

		$this->settings[$model->alias] = array_merge($this->settings[$model->alias], $config);
		$this->settings[$model->alias]['withModel'] = $this->settings[$model->alias]['taggedClass'];
		extract($this->settings[$model->alias]);

		$model->bindModel(array('hasAndBelongsToMany' => array(
			$tagAlias => array(
				'className' => $tagClass,
				'foreignKey' => $foreignKey,
				'associationForeignKey' => $associationForeignKey,
				'unique' => true,
				'conditions' => array(
					'Tagged.model' => $model->name),
				'fields' => '',
				'dependent' => true,
				'with' => $withModel))), $resetBinding);

		$model->$tagAlias->bindModel(array('hasMany' => array(
			$taggedAlias => array(
				'className' => $taggedClass))), $resetBinding);
	}

/**
 * Disassembles the incoming tag string by its separator and identifiers and trims the tags
 *
 * @param object $model Model instance
 * @param string $string incoming tag string
 * @param string $separator separator character
 * @return array Array of 'tags' and 'identifiers', use extract to get both vars out of the array if needed
 */
	public function disassembleTags(Model $model, $string = '', $separator = ',') {
		$array = explode($separator, $string);

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
				$key = $this->multibyteKey($model, $tag);
				if (empty($tags[$key]) && (empty($identifiers[$key]) || !in_array($identifier, $identifiers[$key]))) {
					$tags[] = array('name' => $tag, 'identifier' => $identifier, 'keyname' => $key);
					$identifiers[$key][] = $identifier;
				}
			}
		}

		return compact('tags', 'identifiers');
	}

/**
 * Saves a string of tags
 *
 * @param AppModel $model
 * @param string $string comma separeted list of tags to be saved
 *		Tags can contain special tokens called `identifiers´ to namespace tags or classify them into catageories.
 *		A valid string is "foo, bar, cakephp:special". The token `cakephp´ will end up as the identifier or category for the tag `special´
 * @param mixed $foreignKey the identifier for the record to associate the tags with
 * @param boolean $update true will remove tags that are not in the $string, false wont
 * do this and just add new tags without removing existing tags associated to
 * the current set foreign key
 * @return array
 */
	public function saveTags(Model $model, $string = null, $foreignKey = null, $update = true) {
		if (is_string($string) && !empty($string) && (!empty($foreignKey) || $foreignKey === false)) {
			$tagAlias = $this->settings[$model->alias]['tagAlias'];
			$taggedAlias = $this->settings[$model->alias]['taggedAlias'];
			$tagModel = $model->{$tagAlias};

			extract($this->disassembleTags($model, $string, $this->settings[$model->alias]['separator']));

			if (!empty($tags)) {
				$existingTags = $tagModel->find('all', array(
					'contain' => array(),
					'conditions' => array(
						$tagAlias . '.keyname' => Set::extract($tags, '{n}.keyname')),
					'fields' => array(
						$tagAlias . '.identifier',
						$tagAlias . '.keyname',
						$tagAlias . '.name',
						$tagAlias . '.id')));

				if (!empty($existingTags)) {
					foreach ($existingTags as $existing) {
						$existingTagKeyNames[] = $existing[$tagAlias]['keyname'];
						$existingTagIds[] = $existing[$tagAlias]['id'];
						$existingTagIdentifiers[$existing[$tagAlias]['keyname']][] = $existing[$tagAlias]['identifier'];
					}
					$newTags = array();
					foreach ($tags as $possibleNewTag) {
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
					$tagged = $tagModel->{$taggedAlias}->find('all', array(
						'contain' => array(),
						'conditions' => array(
							$taggedAlias . '.model' => $model->name,
							$taggedAlias . '.foreign_key' => $foreignKey,
							$taggedAlias . '.language' => Configure::read('Config.language'),
							$taggedAlias . '.tag_id' => $existingTagIds),
						'fields' => 'Tagged.tag_id'));

					$deleteAll = array(
						$taggedAlias . '.foreign_key' => $foreignKey,
						$taggedAlias . '.model' => $model->name);

					if (!empty($tagged)) {
						$alreadyTagged = Set::extract($tagged, "{n}.{$taggedAlias}.tag_id");
						$existingTagIds = array_diff($existingTagIds, $alreadyTagged);
						$deleteAll['NOT'] = array($taggedAlias . '.tag_id' => $alreadyTagged);
					}

					$oldTagIds = array();

					if ($update == true) {
						$oldTagIds = $tagModel->{$taggedAlias}->find('all', array(
							'contain' => array(),
							'conditions' => array(
								$taggedAlias . '.model' => $model->name,
								$taggedAlias . '.foreign_key' => $foreignKey,
								$taggedAlias . '.language' => Configure::read('Config.language')),
							'fields' => 'Tagged.tag_id'));

						$oldTagIds = Set::extract($oldTagIds, '/Tagged/tag_id');
						$tagModel->{$taggedAlias}->deleteAll($deleteAll, false);
					} elseif ($this->settings[$model->alias]['taggedCounter'] && !empty($alreadyTagged)) {
						$tagModel->{$taggedAlias}->updateAll(array('times_tagged' => 'times_tagged + 1'), array('Tagged.tag_id' => $alreadyTagged));
					}

					foreach ($existingTagIds as $tagId) {
						$data[$taggedAlias]['tag_id'] = $tagId;
						$data[$taggedAlias]['model'] = $model->name;
						$data[$taggedAlias]['foreign_key'] = $foreignKey;
						$data[$taggedAlias]['language'] = Configure::read('Config.language');
						$tagModel->{$taggedAlias}->create($data);
						$tagModel->{$taggedAlias}->save();
					}

					if ($this->settings[$model->alias]['cacheOccurrence']) {
						$newTagIds = $tagModel->{$taggedAlias}->find('all', array(
							'contain' => array(),
							'conditions' => array(
								$taggedAlias . '.model' => $model->name,
								$taggedAlias . '.foreign_key' => $foreignKey,
								$taggedAlias . '.language' => Configure::read('Config.language')),
							'fields' => array(
								$taggedAlias.  '.tag_id')));

						if (!empty($newTagIds)) {
							$newTagIds = Set::extract($newTagIds, '{n}.Tagged.tag_id');
						}

						$this->cacheOccurrence($model, array_merge($oldTagIds, $newTagIds));
					}
				}
			}
			return true;
		}
		return false;
	}

/**
 * Cache the weight or occurence of a tag in the tags table
 *
 * @param \Model|object $model instance of a model
 * @param $tagIds
 * @return void
 */
	public function cacheOccurrence(Model $model, $tagIds) {
		if (is_string($tagIds) || is_int($tagIds)) {
			$tagIds = array($tagIds);
		}

		foreach ($tagIds as $tagId) {
			$fieldName = Inflector::underscore($model->name) . '_occurrence';
			$tagModel = $model->{$this->settings[$model->alias]['tagAlias']};
			$taggedModel = $tagModel->{$this->settings[$model->alias]['taggedAlias']};
			$data = array($tagModel->primaryKey => $tagId);

			if ($tagModel->hasField($fieldName)) {
				$data[$fieldName] = $taggedModel->find('count', array(
					'conditions' => array(
						'Tagged.tag_id' => $tagId,
						'Tagged.model' => $model->name)));
			}

			$data['occurrence'] = $taggedModel->find('count', array(
				'conditions' => array(
					'Tagged.tag_id' => $tagId)));

			$tagModel->save($data, array('validate' => false, 'callbacks' => false));
		}
	}

/**
 * Creates a multibyte safe unique key
 *
 * @param object Model instance
 * @param string Tag name string
 * @return string Multibyte safe key string
 */
	public function multibyteKey(Model $model, $string = null) {
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
 */
	public function tagArrayToString(Model $model, $data = null) {
		if ($data) {
			return join($this->settings[$model->alias]['separator'] . ' ', Set::extract($data, '{n}.name'));
		}
		return '';
	}

/**
 * afterSave callback
 *
 * @param AppModel $model
 */
	public function afterSave(Model $model, $created, $options = array()) {
		$hasTags = !empty($model->data[$model->alias][$this->settings[$model->alias]['field']]);
		if ($this->settings[$model->alias]['automaticTagging'] == true && $hasTags) {
			$this->saveTags($model, $model->data[$model->alias][$this->settings[$model->alias]['field']], $model->id);
		} else if (!$hasTags && $this->settings[$model->alias]['deleteTagsOnEmptyField']) {
			$this->deleteTagged($model);
		}
	}

/**
 * Delete associated Tags if record has no tags and deleteTagsOnEmptyField is true
 * @param object Model instance
 */
	public function deleteTagged(Model $model) {
		extract($this->settings[$model->alias]);
		$tagModel = $model->{$tagAlias};
		$tagModel->{$taggedAlias}->deleteAll(
			array(
				$taggedAlias . '.model' => $model->name,
				$taggedAlias . '.foreign_key' => $model->id,
			)
		);
	}

/**
 * afterFind Callback
 *
 * @param AppModel $model
 * @param array $results
 * @param boolean $primary
 * @return array
 */
	public function afterFind(Model $model, $results, $primary = false) {
		extract($this->settings[$model->alias]);
		foreach ($results as $key => $row) {
			$row[$model->alias][$field] = '';
			if (isset($row[$tagAlias]) && !empty($row[$tagAlias])) {
				$row[$model->alias][$field] = $this->tagArrayToString($model, $row[$tagAlias]);
				if ($unsetInAfterFind == true) {
					unset($row[$tagAlias]);
				}
			}
			$results[$key] = $row;
		}
		return $results;
	}
}
