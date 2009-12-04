<?php
/* SVN FILE: $Id: tags_controller.php 1077 2009-09-07 15:47:55Z skie $ */
/**
 * Short description for file.
 *
 * Long description for file
 *
 * PHP versions 4 and 5
 *
 * Converge Application Platform
 *
 * Copyright 2007-2008, Cake Development Corporation
 * 							1785 E. Sahara Avenue, Suite 490-423
 * 							Las Vegas, Nevada 89104
 *
 * You may obtain a copy of the License at:
 * License page: http://projects.cakedc.com/licenses/TBD  TBD
 * Copyright page: http://converge.cakedc.com/copyright/
 *
 * @filesource
 * @copyright		Copyright 2007-2008, Cake Development Corporation
 * @link				http://converge.cakedc.com/ Converge Application Platform
 * @package			converge
 * @subpackage		converge.controllers
 * @since			Converge v 1.0.0.0
 * @version			$Revision: 1077 $
 * @modifiedby		$LastChangedBy: skie $
 * @lastmodified	$Date: 2009-09-07 17:47:55 +0200 (Mo, 07 Sep 2009) $
 * @license			http://projects.cakedc.com/licenses/TBD  TBD
 */
/**
 * Short description for class.
 *
 * @package		converge
 * @subpackage	converge.controllers
 */
class TagsController extends TagsAppController {
/**
 * Name
 *
 * @var string $name
 * @access public
 */
	public $name = 'Tags';
/**
 * Helpers
 *
 * @var array $name
 * @access public
 */
	public $helpers = array('Html', 'Form');
/**
 * 
 */
	public function index() {
		$this->Tag->recursive = 0;
		$this->set('tags', $this->paginate());
	}
/**
 * 
 */
	public function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Tag.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('tag', $this->Tag->read(null, $id));
	}
/**
 * 
 */
	public function admin_index() {
		$this->Tag->recursive = 0;
		$this->set('tags', $this->paginate());
	}
/**
 * Views a single tag
 *
 * @param string tag UUID
 * @return void
 * @access public
 */
	public function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Tag.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('tag', $this->Tag->read(null, $id));
	}
/**
 * Adds one or more tags
 *
 * @return void
 * @access public
 */
	public function admin_add() {
		if (!empty($this->data)) {
			$this->Tag->create();
			if ($this->Tag->save($this->data)) {
				$this->Session->setFlash(__('The Tag has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Tag could not be saved. Please, try again.', true));
			}
		}
	}
/**
 * Edits a tag
 *
 * @param string tag UUID
 * @return void
 * @access public
 */
	public function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Tag', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Tag->save($this->data)) {
				$this->Session->setFlash(__('The Tag has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Tag could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Tag->read(null, $id);
		}
	}
/**
 * Deletes a tag
 *
 * @param string tag UUID
 * @return void
 * @access public
 */
	public function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Tag', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Tag->delete($id)) {
			$this->Session->setFlash(__('Tag deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}
}
?>
