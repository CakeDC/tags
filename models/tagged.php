<?php
class Tagged extends TagsAppModel {
/**
 * Name
 *
 * @var string
 * @access public
 */
	public $name = 'Tagged';

/**
 * Table that is used
 *
 * @var string
 * @access public
 */
	public $useTable = 'tagged';

/**
 * Find methodes
 *
 * @var array
 * @access public
 */
	public $_findMethods = array(
		'cloud' => true);

/**
 * belongsTo associations
 *
 * @var string
 * @access public
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
 * @access public
 */
	public function _findCloud($state, $query, $results = array()) {
		if ($state == 'before') {
			$options = array(
				'minSize' => 10,
				'maxSize' => 20,
				'page' => null,
				'limit' => null,
				'order' => null,
				'joins' => null,
				'offset' => null,
				'contain' => 'Tag',
				'conditions' => array(),
				'fields' => 'Tag.*, Tagged.tag_id, COUNT(*) AS occurrence',
				'group' => 'Tagged.tag_id');

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

}
?>