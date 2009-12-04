<?php
/* SVN FILE: $Id: tag_cloud.php 1273 2009-10-06 13:51:44Z burzum $ */
/*
 * Tag cloud helper
 * 
 * Outputs a formatted tag cloud based on the weight of the tags 
 */
class TagCloudHelper extends AppHelper {
/**
 * Other helpers to load
 *
 * @var public $helpers
 */
	public $helpers = array('Html');
/**
 * Method to output a tag-cloud formatted based on the weight of the tags
 *
 * @todo find a nice way to create the url and its options like css and id and parse %size% in their values
 *
 * @param array $tags
 * @return string
 */
	public function display($tags = null, $options = array()) {
		if (empty($tags) || !is_array($tags)) {
			return '';
		}
		$defaults = array(
			'shuffle' => true,
			'extract' => '{n}.Tag.weight',
			'before' => '',
			'after' => '',
			'link' => false,
			'maxSize' => 160,
			'minSize' => 80);
		$options = array_merge($defaults, $options);

		$weights = Set::extract($tags, $options['extract']);
		$maxWeight = max($weights);
		$minWeight = min($weights);

		// find the range of values
		$spread = $maxWeight - $minWeight;
		if (0 == $spread) {
			$spread = 1;
		}

		if ($options['shuffle'] == true) {
			shuffle($tags);
		}

		$cloud = null;
		foreach ($tags as $tag) {
			$size = $options['minSize'] + (($tag['Tag']['weight'] - $minWeight) * (($options['maxSize'] - $options['minSize']) / ($spread)));
			$size = ceil($size);
			$cloud .= $this->_replace($options['before'], $size);
			$cloud .= $this->Html->link($tag['Tag']['name'], array('controller' => 'search', 'by' => $tag['Tag']['keyname']), array('id' => 'tag-' . $tag['Tag']['id'])).' ';
			$cloud .= $this->_replace($options['after'], $size);
		}
		return $cloud;
	}
/**
 * Replaces %size% in strings with the calculated "size" of the tag
 *
 * @return string
 * @access protected
 */
	protected function _replace($string, $size) {
		return str_replace("%size%", $size, $string);
	}
}
?>