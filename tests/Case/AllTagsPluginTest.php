<?php
class AllTagsPluginTest extends PHPUnit_Framework_TestSuite {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new PHPUnit_Framework_TestSuite('All Tags Plugin Tests');

		$basePath = APP . 'plugins' . DS . 'tags' . DS . 'tests' . DS . 'Case' . DS;
		// controllers
		$suite->addTestFile($basePath . 'Controller' . DS . 'tags_controller.test.php');
		// behaviors
		$suite->addTestFile($basePath . 'Behavior' . DS . 'taggable.test.php');
		// models
		$suite->addTestFile($basePath . 'Model' . DS . 'tag.test.php');
		$suite->addTestFile($basePath . 'Model' . DS . 'tagged.test.php');
		// helpers
		$suite->addTestFile($basePath . 'Helper' . DS . 'tag_cloud.test.php');
		return $suite;
	}
}