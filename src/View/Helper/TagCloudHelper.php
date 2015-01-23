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

namespace Tags\View\Helper;

use \Cake\View\Helper;

/**
 * Tag cloud helper
 *
 * @package tags
 * @subpackage tags.views.helpers
 */
class TagCloudHelper extends Helper {

/**
 * Other helpers to load
 *
 * @var public $helpers
 */
	public $helpers = array(
		'Html'
	);

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
 *  - url: an array containing the default url
 *  - named: the named parameter used to send the tag [default: by]
 * @return string
 */
	public function display($tags = null, $options = array()) {
		if (empty($tags) || !is_array($tags)) {
			return '';
		}
		$defaults = array(
			'tagModel' => 'Tag',
			'shuffle' => true,
			'extract' => '{n}.Tag.weight',
			'before' => '',
			'after' => '',
			'maxSize' => 160,
			'minSize' => 80,
			'url' => array(
				'controller' => 'search'
			),
			'named' => 'by'
		);
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
			$data = Set::extract(array($tag), $options['extract']);
			$tagWeight = array_pop($data);

			$size = $options['minSize'] + (($tagWeight - $minWeight) * (($options['maxSize'] - $options['minSize']) / ($spread)));
			$size = $tag[$options['tagModel']]['size'] = ceil($size);

			$cloud .= $this->_replace($options['before'], $size);
			$cloud .= $this->Html->link($tag[$options['tagModel']]['name'], $this->_tagUrl($tag, $options), array('id' => 'tag-' . $tag[$options['tagModel']]['id'])) . ' ';
			$cloud .= $this->_replace($options['after'], $size);
		}

		return $cloud;
	}

/**
 * Generates the URL for a tag
 *
 * @param array
 * @param array
 * @return array|string
 */
	protected function _tagUrl($tag, $options) {
		$options['url'][$options['named']] = $tag[$options['tagModel']]['keyname'];
		return $options['url'];
	}

/**
 * Replaces %size% in strings with the calculated "size" of the tag
 *
 * @param string
 * @param float
 * @return string
 */
	protected function _replace($string, $size) {
		return str_replace("%size%", $size, $string);
	}
}
