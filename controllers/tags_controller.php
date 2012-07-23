<?php
/**
 * Copyright 2009-2012, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2009-2012, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Tags Controller
 *
 * @package tags
 * @subpackage tags.controllers
 */
class TagsController extends TagsAppController {

/**
 * Name
 *
 * @var string
 */
	public $name = 'Tags';

/**
 * Helpers
 *
 * @var array
 */
	public $helpers = array('Html', 'Form');

/**
 * Index action
 *
 * @return void
 */
	public function index() {
		$this->Tag->recursive = 0;
		$this->set('tags', $this->paginate());
	}

/**
 * View
 *
 * @param string
 * @return void
 */
	public function view($keyName = null) {
		try {
			$this->set('tag', $this->Tag->view($keyName));
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect('/');
		}
	}

/**
 * Admin Index
 *
 * @return void
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
 */
	public function admin_view($keyName) {
		try {
			$this->set('tag', $this->Tag->view($keyName));
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect('/');
		}
	}

/**
 * Adds one or more tags
 *
 * @return void
 */
	public function admin_add() {
		if (!empty($this->data)) {
			if ($this->Tag->add($this->data)) {
				$this->Session->setFlash(__d('tags', 'The Tags has been saved.', true));
				$this->redirect(array('action' => 'index'));
			}
		}
	}

/**
 * Edits a tag
 *
 * @param string tag UUID
 * @return void
 */
	public function admin_edit($tagId = null) {
		try {
			$result = $this->Tag->edit($tagId, $this->data);
			if ($result === true) {
				$this->Session->setFlash(__d('tags', 'Tag saved.', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->data = $result;
			}
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect(array('action' => 'index'));
		}

		if (empty($this->data)) {
			$this->data = $this->Tag->data;
		}
	}

/**
 * Deletes a tag
 *
 * @param string tag UUID
 * @return void
 */
	public function admin_delete($id = null) {
		if ($this->Tag->delete($id)) {
			$this->Session->setFlash(__d('tags', 'Tag deleted.', true));
		} else {
			$this->Session->setFlash(__d('tags', 'Invalid Tag.', true));
		}
		$this->redirect(array('action' => 'index'));
	}
}
