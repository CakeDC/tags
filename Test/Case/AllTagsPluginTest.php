<?php
class AllTagsPluginTest extends PHPUnit_Framework_TestSuite {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new PHPUnit_Framework_TestSuite('All Tags Plugin Tests');

		$basePath = CakePlugin::path('Tags') . DS . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($basePath);
		return $suite;
	}

}