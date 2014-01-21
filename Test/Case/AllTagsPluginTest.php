<?php
class AllTagsPluginTest extends PHPUnit_Framework_TestSuite {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$path = CakePlugin::path('Tags') . DS . 'Test' . DS . 'Case' . DS;

		$Suite = new CakeTestSuite('All Tags Plugin Tests');
		$Suite->addTestDirectory($path . DS . 'Controller');
		$Suite->addTestDirectory($path . DS . 'View' . DS . 'Helper');
		$Suite->addTestDirectory($path . DS . 'Model');
		return $Suite;
	}

}