<?php
class AllTagsPluginTest extends PHPUnit_Framework_TestSuite {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new PHPUnit_Framework_TestSuite('All Tags Plugin Tests');

		$basePath = APP . 'plugins' . DS . 'tags' . DS . 'tests' . DS . 'cases' . DS;
		// controllers
		$suite->addTestFile($basePath . 'controllers' . DS . 'tags_controller.test.php');
		// behaviors
		$suite->addTestFile($basePath . 'behaviors' . DS . 'taggable.test.php');
		// models
		$suite->addTestFile($basePath . 'models' . DS . 'tag.test.php');
		$suite->addTestFile($basePath . 'models' . DS . 'tagged.test.php');
		// helpers
		$suite->addTestFile($basePath . 'helpers' . DS . 'tag_cloud.test.php');
		return $suite;
	}
}