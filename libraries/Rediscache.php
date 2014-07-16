<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * Rediscache 
 *
 * A CodeIgniter library for a simple cache system using redis.
 *
 * @package             CodeIgniter
 * @category            Libraries
 * @author              ashiina
 * @link                        https://github.com/ashiina/codeigniter-rediscache
 * @license         http://www.opensource.org/licenses/mit-license.html
 * 
 * Copyright (c) 2011 Ahmad Shiina and contributers
 */
class Rediscache {

	public function __construct($params=null)
	{
		$this->CI =& get_instance();
		$this->CI->load->library('redis');
	}

	/*
	 * Cache usage control : 
	 * Set this to true to enable rediscache
	 */
	protected $_USE_CACHE=true;

	/*
	 * Universal prefix for the cache keys 
	 */
	protected $_CACHE_KEY_PREFIX='__CACHE';

	/*
	 * Set cache
	 * 
	 * Uses redis to set a cache.
	 * Can cache any type that can be serialized. Refer to the PHP manual for what types can be serialized:
	 * http://php.net/manual/en/function.serialize.php
	 * The default expire is set to 43200 (12hours). 
	 * 
	 * @param key : string
	 * @param value : mixed
	 * @param expire : int
	 * @return bool
	*/
	public function set ($key, $value, $expire=43200) { 
		if (!$this->_USE_CACHE) {
			return false;
		}

		// dont allow if resource
		if (is_resource($value)) {
			return false;
		}

		// try to serialize val
		$serializedVal;
		try {
			$serializedVal = urlencode(serialize($value));
		} catch (Exception $e) {
			return false;
		}

		// get key
		$cacheKey = $this->_get_cache_key($key);

		$result = $this->CI->redis->set($cacheKey, $serializedVal );
		$result = $this->CI->redis->command("EXPIRE", $cacheKey, $expire);
		if ($result) {
			return true;
		} else {
			return false;
		}
	}

	/*
	 * Get cache
	 * 
	 * Uses redis to get a cache with the designated key.
	 * If the key does not exist, or if cache is disabled, it will return false.
	 * 
	 * @param key : string
	 * @return mixed
	*/
	public function get ($key) {
		if (!$this->_USE_CACHE) {
			return false;
		}

		$cacheKey = $this->_get_cache_key($key);
		$serializedResult = $this->CI->redis->get($cacheKey);

		if ($serializedResult) {
			return unserialize(urldecode($serializedResult));
		} else {
			return false;
		}
	}

	/*
	 * Delete cache
	 * 
	 * Uses redis to delete a cache with the designated key.
	 * It will return true if the cache is successfully deleted.
	 * Otherwise it will return false.
	 * 
	 * @param key : string
	 * @return bool
	*/
	public function del ($key) {
		$cacheKey = $this->_get_cache_key($key);
		$result = $this->CI->redis->del($cacheKey);
		if ($result) {
			return true;
		} else {
			return false;
		}
	}

	// Format key into cache key
	protected function _get_cache_key ($targetKey) {
		return implode('.', array($this->_CACHE_KEY_PREFIX, $targetKey));
	}

}

