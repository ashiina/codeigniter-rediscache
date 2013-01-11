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

		$testVar = 'testvar';

		$testArr = array(
			'hoge' => 'foo',
			'fuga' => 'bar'
		); 

		$obj = new Testobj;

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
		$this->unit->run($this->rediscache->set('rediscache.test.key1', $obj, 10000), true, 'set object');
		// get object
		$this->unit->run($this->rediscache->get('rediscache.test.key1')->var, $obj->var, 'get object');
		// del object
		$this->unit->run($this->rediscache->del('rediscache.test.key1'), true, 'del object');

		echo $this->unit->report();

	}

}

class Testobj {
	public $var = 'var';
}

