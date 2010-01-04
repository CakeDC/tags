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
 * @param array $tags
 * @param array $options Display options. Valid keys are:
 * 	- shuffle: true to shuffle the tag list, false to display them in the same order than passed [default: true]
 *  - extract: Set::extract() compatible format string. Path to extract weight values from the $tags array [default: {n}.Tag.weight]
 *  - before: string to be displayed before each generated link. "%size%" will be replaced with tag size calculated from the weight [default: empty]
 *  - after: string to be displayed after each generated link. "%size%" will be replaced with tag size calculated from the weight [default: empty]
 *  - maxSize: size of the heaviest tag [default: 160]
 *  - minSize: size of the lightest tag [default: 80]
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
