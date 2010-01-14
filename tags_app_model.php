<?php
/* SVN FILE: $Id: tags_app_model.php 1027 2009-08-28 18:50:51Z renan.saddam $ */
/**
 * Short description for class.
 *
 * @package		converge.plugins
 * @subpackage	converge.plugins.tags
 */
class TagsAppModel extends AppModel {
/**
 * Plugin name
 *
 * @var string $plugin
 * @access public
 */
	public $plugin = 'Tags';
	
/**
 * Customized paginateCount method
 *
 * @param array
 * @param integer
 * @param array
 * @return 
 * @access public
 */
	public function paginateCount($conditions = array(), $recursive = 0, $extra = array()) {
		$parameters = compact('conditions');
		if ($recursive != $this->recursive) {
			$parameters['recursive'] = $recursive;
		}
		if (isset($extra['type']) && isset($this->_findMethods[$extra['type']])) {
			$extra['operation'] = 'count';
			return $this->find($extra['type'], array_merge($parameters, $extra));
		} else {
			return $this->find('count', array_merge($parameters, $extra));
		}
	}

}
?>