<?php

/**
 * Test for rediscache 
 */
if (! defined('BASEPATH')) exit('No direct script access');

class Rediscache_test extends CI_Controller {

	public function index () {
		$this->load->library('unit_test');
		$this->load->library('redis');
		$this->load->library('rediscache');
		$this->unit->use_strict(TRUE);

		// variable
		$testVar = 'testvar';

		// array
		$testArr = array(
			'hoge' => 'foo',
			'fuga' => 'bar'
		); 

		// object
		$testObj = new Testobj;

		// function
		$testFunc = function(){};

		// resource
		$testRes = curl_init();

		// set string
		$this->unit->run($this->rediscache->set('rediscache.test.key1', $testVar, 10000), true, 'set string');
		// get string
		$this->unit->run($this->rediscache->get('rediscache.test.key1'), $testVar, 'get string');
		// del string
		$this->unit->run($this->rediscache->del('rediscache.test.key1'), true, 'del string');

		// set array
		$this->unit->run($this->rediscache->set('rediscache.test.key1', $testArr, 10000), true, 'set array');
		// get array
		$this->unit->run($this->rediscache->get('rediscache.test.key1'), $testArr, 'get array');
		// del array
		$this->unit->run($this->rediscache->del('rediscache.test.key1'), true, 'del array');

		// set object
		$this->unit->run($this->rediscache->set('rediscache.test.key1', $testObj, 10000), true, 'set object');
		// get object
		$this->unit->run($this->rediscache->get('rediscache.test.key1')->var, $testObj->var, 'get object');
		// del object
		$this->unit->run($this->rediscache->del('rediscache.test.key1'), true, 'del object');

		// set function (should be false)
		$this->unit->run($this->rediscache->set('rediscache.test.key1', $testFunc, 10000), false, 'set function (should be false)');
		// get function (should be false)
		$this->unit->run($this->rediscache->get('rediscache.test.key1'), false, 'get function (should be false)');

		// set resource (should be false)
		$this->unit->run($this->rediscache->set('rediscache.test.key1', $testRes, 10000), false, 'set resource (should be false)');
		// get resource (should be false)
		$this->unit->run($this->rediscache->get('rediscache.test.key1'), false, 'get resource (should be false)');

		echo $this->unit->report();

	}

}

class Testobj {
	public $var = 'var';
}

