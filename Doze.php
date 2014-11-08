<?php
/**
 * Doze library autoloader.
 *
 * @author    Eric Mann <eric.mann@10up.com>
 * @copyright 2014 Eric Mann, 10up
 * @license   http://www.opensource.org/licenses/mit-license.html
 * @version   1.0.0
 */
namespace TenUp\Doze\v1_0_0;
if ( version_compare( PHP_VERSION, "5.3", "<" ) ) {
	trigger_error( "Doze requires PHP version 5.3.0 or higher", E_USER_ERROR );
}

// Require files
if ( ! class_exists( __NAMESPACE__ . '\\API' ) ) {
	require_once __DIR__ . '/php/Request.php';
	require_once __DIR__ . '/php/Response.php';
	require_once __DIR__ . '/php/Resource.php';
	require_once __DIR__ . '/php/API.php';
}