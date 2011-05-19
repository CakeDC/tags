<?php
class AllTagsPluginTest extends PHPUnit_Framework_TestSuite {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new PHPUnit_Framework_TestSuite('All Tags Plugin Tests');

		$basePath = App::pluginPath('Tags') . DS . 'Test' . DS . 'Case' . DS;
		// controllers
		$suite->addTestFile($basePath . 'Controller' . DS . 'TagsControllerTest.php');
		// behaviors
		$suite->addTestFile($basePath . 'Behavior' . DS . 'TaggableTest.php');
		// models
		$suite->addTestFile($basePath . 'Model' . DS . 'TagTest.php');
		$suite->addTestFile($basePath . 'Model' . DS . 'TaggedTest.php');
		// helpers
		$suite->addTestFile($basePath . 'Helper' . DS . 'TagCloudTest.php');
		return $suite;
	}

}