<?php
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
 * Index
 *
 * @return void
 * @access public
 */
	public function index() {
		$this->Tag->recursive = 0;
		$this->set('tags', $this->paginate());
	}

/**
 * Index
 *
 * @param string
 * @return void
 * @access public
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
 * @access public
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
 * @access public
 */
	public function admin_add() {
		if (!empty($this->data)) {
			if ($this->Tag->add($this->data)) {
				$this->Session->setFlash(__('The Tags has been saved', true));
				$this->redirect(array('action' => 'index'));
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
	public function admin_edit($tagId = null) {
		try {
			$result = $this->Tag->edit($tagId, $this->data);
			if ($result === true) {
				$this->Session->setFlash(__d('tags', 'Tag saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->data = $result;
			}
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect(array('action' => 'index'));
		}

		if (empty($this->data)) {
			$this->data = $result;
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
		if ($this->Tag->delete($id)) {
			$this->Session->setFlash(__('Tag deleted', true));
		} else {
			$this->Session->setFlash(__('Invalid Tag', true));
		}
		$this->redirect(array('action' => 'index'));
	}

}
?>