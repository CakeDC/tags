<?php
class ArticleFixture extends CakeTestFixture {

/**
 * name property
 *
 * @var string 'AnotherArticle'
 * @access public
 */
	public $name = 'Article';

/**
 * fields property
 *
 * @var array
 * @access public
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => false));

/**
 * records property
 *
 * @var array
 * @access public
 */
	public $records = array(
		array(
			'id' => 1,
			'title' => 'First Article'),
		array(
			'id' => 2,
			'title' => 'Second Article'),
		array(
			'id' => 3,
			'title' => 'Third Article'));

}
?>