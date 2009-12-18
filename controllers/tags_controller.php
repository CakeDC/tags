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
 * 
 */
	public function index() {
		$this->Tag->recursive = 0;
		$this->set('tags', $this->paginate());
	}

/**
 * 
 */
	public function view($keyName = null) {
		
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
		if ($this->Tag->delete($id)) {
			$this->Session->setFlash(__('Tag deleted', true));
		} else {
			$this->Session->setFlash(__('Invalid Tag', true));
		}
		$this->redirect(array('action' => 'index'));
	}

}
?>