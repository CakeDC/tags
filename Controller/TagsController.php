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
 * Uses
 *
 * @var array
 */
	public $uses = array('Tags.Tag');

/**
 * Components
 *
 * @var array
 */
	public $components = array('Session');

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
		if (!empty($this->request->data)) {
			if ($this->Tag->add($this->request->data)) {
				$this->Session->setFlash(__d('tags', 'The Tags has been saved.'));
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
			$result = $this->Tag->edit($tagId, $this->request->data);
			if ($result === true) {
				$this->Session->setFlash(__d('tags', 'Tag saved.'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->request->data = $result;
			}
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect(array('action' => 'index'));
		}

		if (empty($this->request->data)) {
			$this->request->data = $this->Tag->data;
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
			$this->Session->setFlash(__d('tags', 'Tag deleted.'));
		} else {
			$this->Session->setFlash(__d('tags', 'Invalid Tag.'));
		}
		$this->redirect(array('action' => 'index'));
	}
}
