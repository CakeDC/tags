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
App::uses('TagsAppController', 'Tags.Controller');

/**
 * Tags Controller
 *
 * @package tags
 * @subpackage tags.controllers
 */
class TagsController extends TagsAppController {

/**
 * Uses
 *
 * @var array
 */
	public $uses = array(
		'Tags.Tag'
	);

/**
 * Components
 *
 * @var array
 */
	public $components = array(
		'Session',
		'Paginator'
	);

/**
 * Helpers
 *
 * @var array
 */
	public $helpers = array(
		'Html', 'Form'
	);

/**
 * Index action
 *
 * @return void
 */
	public function index() {
		$this->{$this->modelClass}->recursive = 0;
		$this->set('tags', $this->Paginator->paginate());
	}

/**
 * View
 *
 * @param string
 * @return void
 */
	public function view($keyName = null) {
		try {
			$this->set('tag', $this->{$this->modelClass}->view($keyName));
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
		 $this->{$this->modelClass}->recursive = 0;
		$this->set('tags', $this->Paginator->paginate());
	}

/**
 * Views a single tag
 *
 * @param string tag UUID
 * @return void
 */
	public function admin_view($keyName) {
		try {
			$this->set('tag', $this->{$this->modelClass}->view($keyName));
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
			if ($this->{$this->modelClass}->add($this->request->data)) {
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
			$result = $this->{$this->modelClass}->edit($tagId, $this->request->data);
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
			$this->request->data = $this->{$this->modelClass}->data;
		}
	}

/**
 * Deletes a tag
 *
 * @param string tag UUID
 * @return void
 */
	public function admin_delete($id = null) {
		if ($this->{$this->modelClass}->delete($id)) {
			$this->Session->setFlash(__d('tags', 'Tag deleted.'));
		} else {
			$this->Session->setFlash(__d('tags', 'Invalid Tag.'));
		}
		$this->redirect(array('action' => 'index'));
	}
}
