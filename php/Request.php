<?php
namespace TenUp\Doze\v1_0_0;

/**
 * Standard request abstraction
 *
 * @package Doze
 */
class Request {
	/**
	 * Get the current request, either from the internal cache or by parsing super-globals
	 *
	 * @return Request
	 */
	public static function current() {

	}

	/**
	 * @var string Request method verb (all caps)
	 */
	public $method;

	/**
	 * Get a server super-global.
	 *
	 * @param string $key
	 *
	 * @return string
	 */
	public function globals( $key ) {

	}
}