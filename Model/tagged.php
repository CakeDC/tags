<?php
/**
 * Copyright 2009-2010, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2009-2010, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Tagged model
 *
 * @package tags
 * @subpackage tags.models
 */
class Tagged extends TagsAppModel {

/**
 * Name
 *
 * @var string
 */
	public $name = 'Tagged';

/**
 * Table that is used
 *
 * @var string
 */
	public $useTable = 'tagged';

/**
 * Find methodes
 *
 * @var array
 */
	public $findMethods = array(
		'cloud' => true,
		'tagged' => true);

/**
 * belongsTo associations
 *
 * @var string
 */
	public $belongsTo = array(
		'Tag' => array(
			'className' => 'Tags.Tag'));

/**
 * Returns a tag cloud
 *
 * The result contains a "weight" field which has a normalized size of the tag
 * occurrence set. The min and max size can be set by passing 'minSize" and
 * 'maxSize' to the query. This value can be used in the view to controll the
 * size of the tag font.
 *
 * @todo Ideas to improve this are welcome
 * @param string
 * @param array
 * @param array
 * @return array
 */
	public function _findCloud($state, $query, $results = array()) {
		if ($state == 'before') {
			$options = array(
				'minSize' => 10,
				'maxSize' => 20,
				'page' => '',
				'limit' => '',
				'order' => '',
				'joins' => array(),
				'offset' => '',
				'contain' => 'Tag',
				'conditions' => array(),
				'fields' => 'Tagged.tag_id, Tag.id, Tag.identifier, Tag.name, Tag.keyname, Tag.weight, COUNT(*) AS occurrence',
				'group' => array('Tagged.tag_id', 'Tag.id', 'Tag.identifier', 'Tag.name', 'Tag.keyname', 'Tag.weight'));

			foreach ($query as $key => $value) {
				if (!empty($value)) {
					$options[$key] = $value;
				}
			}
			$query = $options;

			if (isset($query['model'])) {
				$query['conditions'] = Set::merge($query['conditions'], array('Tagged.model' => $query['model']));
			}

			return $query;
		} elseif ($state == 'after') {
			if (!empty($results) && isset($results[0][0]['occurrence'])) {
				$weights = Set::extract($results, '{n}.0.occurrence');
				$maxWeight = max($weights);
				$minWeight = min($weights);

				$spread = $maxWeight - $minWeight;
				if (0 == $spread) {
					$spread = 1;
				}

				foreach ($results as $key => $result) {
					$size = $query['minSize'] + (($result[0]['occurrence'] - $minWeight) * (($query['maxSize'] - $query['minSize']) / ($spread)));
					$results[$key]['Tag']['occurrence'] = $result[0]['occurrence'];
					$results[$key]['Tag']['weight'] = ceil($size);
				}
			}
			return $results;
		}
	}

/**
 * Find all the Model entries tagged with a given tag
 * 
 * The query must contain a Model name, and can contain a 'by' key with the Tag keyname to filter the results
 * <code>
 * $this->Article->Tagged->find('tagged', array(
 *		'by' => 'cakephp',
 *		'model' => 'Article'));
 * </code
 *
 * @TODO Find a way to populate the "magic" field Article.tags
 * @param string $state
 * @param array $query
 * @param array $results
 * @return mixed Query array if state is before, array of results or integer (count) if state is after
 */
	public function _findTagged($state, $query, $results = array()) {
		if ($state == 'before') {
			if (isset($query['model']) && $Model = ClassRegistry::init($query['model'])) {
				$belongsTo = array(
					$Model->alias => array(
						'className' => $Model->name,
						'foreignKey' => 'foreign_key',
						'conditions' => array(
							$this->alias . '.model' => $Model->alias
						),
					)
				);
				$this->bindModel(compact('belongsTo'), false);

				if (isset($query['operation']) && $query['operation'] == 'count') {
					$query['fields'][] = "COUNT(DISTINCT $Model->alias.$Model->primaryKey)";
				} else {
					$query['fields'][] = "DISTINCT $Model->alias.*";
				}

				if (!empty($query['by'])) {
					$query['conditions'] = array(
						$this->Tag->alias . '.keyname' => $query['by']);
				}
			}
			return $query;
		} elseif ($state == 'after') {
			if (isset($query['operation']) && $query['operation'] == 'count') {
				return array_shift($results[0][0]);
			}
			return $results;
		}
	}
}
