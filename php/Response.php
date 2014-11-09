<?php
namespace TenUp\Doze\v1_0_0;

use TenUp\HTTP\v1_0_0\Header as Header;

/**
 * Generic response object
 *
 * @package Doze
 */
class Response {
	/**
	 *
	 * @uses TenUp\HTTP\v1_0_0\Header\add()
	 *
	 * @param $type
	 */
	public function set_mime_type( $type ) {
		Header\add( 'Content-Type', $type, true );
	}
}