<?php
namespace TenUp\Doze\v1_0_0;

use TenUp\HTTP\v1_0_0\Header as Header;

class API {

	/**
	 * @var array Resource collection
	 */
	protected $resources = array();

	/**
	 * Default constructor
	 *
	 * @uses apply_filters()
	 */
	public function __construct() {
		/**
		 * Allow other components and resources to be registered
		 *
		 * @param array $resources
		 */
		$this->resources = apply_filters( 'doze_resources', $this->resources );
	}

	/**
	 * Wire WordPress hooks where necessary
	 *
	 * @uses add_action()
	 */
	public function hook() {
		add_action( 'init',              array( $this, 'add_api_endpoints' ) );
		add_action( 'template_redirect', array( $this, 'do_api' )            );
	}

	/**
	 * Add rewrite rules to the WordPress permalink system
	 *
	 * @uses add_rewrite_tag()
	 * @uses add_rewrite_rule()
	 */
	public function add_api_endpoints() {
		add_rewrite_tag( '%api_resource%', '([^&]+)' );
		add_rewrite_tag( '%item_id%',      '([^&]+)' );
		add_rewrite_tag( '%time_zone%',      '([^&]+)' );

		$resources = implode( '|', $this->resources );

		add_rewrite_rule( 'api/(' . $resources . ')/([^/]+)/?', 'index.php?api_resource=$matches[1]&item_id=$matches[2]', 'top' );
		add_rewrite_rule( 'api/(' . $resources . ')/?',        'index.php?api_resource=$matches[1]',                      'top' );
	}

	/**
	 * Actually check on our API resource and run appropriate hooks.
	 *
	 * @uses do_action()
	 * @uses wp_die()
	 *
	 * @global \WP_Query $wp_query
	 */
	public function do_api() {
		global $wp_query;

		$resource = $wp_query->get( 'api_resource' );
		if ( in_array( $resource, $this->resources ) ) {
			Header\add( 'HTTP 1.1 200 OK' );
			$this->do_cors();

			// Get the requested resource ID
			$item_id = $wp_query->get( 'item_id' ) ? $wp_query->get( 'item_id' ) : null;

			//Get the requested resources timezone
			$time_zone = $wp_query->get( 'time_zone' ) ? $wp_query->get( 'time_zone' ) : null;

			do_action( 'doze-api-' . $resource, $item_id, $time_zone );

			// The API handler should die() on its own. If not, exit.
			exit();
		}
	}

	/**
	 * Retrieve an authentication header from the given request object.
	 *
	 * @param Request $request
	 *
	 * @return string
	 */
	public function get_auth( $request = null ) {}

	/**
	 * Validate an auth header against database credentials.
	 * The auth header is using HTTP Basic:
	 * i.e. base64( ‘user:password’ )
	 *
	 * @param string     $authentication
	 * @param DataSource $datasource
	 *
	 * @returns bool
	 */
	public function validate_auth( $authentication, $datasource ) {}

	/**
	 * Configure and set up Cross Origin Access Control
	 *
	 * @uses TenUp\HTTP\v1_0_0\Header\add()
	 * @uses Request::current()
	 */
	protected function do_cors() {
		$request = Request::current();

		Header\add( 'Access-Control-Allow-Origin',      '*'                       );
		Header\add( 'Access-Control-Allow-Credentials', 'true'                    );
		Header\add( 'Access-Control-Max-Age',           '86400'                   ); // Cache for one day
		Header\add( 'Access-Control-Allow-Methods',     'GET, POST, OPTIONS'      );
		Header\add( 'Content-Type',                     'application/json',  true );

		$headers = $request->super( 'HTTP_ACCESS_CONTROL_REQUEST_HEADERS' );
		if ( ! empty( $headers ) ) {
			Header\add( 'Access-Control-Allow-Headers', $headers );
		}
	}

	/**
	 * Validate that the request method is in the allowed set of methods for the API endpoint.
	 *
	 * @param array $allowed
	 *
	 * @uses wp_send_json_error()
	 * @uses __()
	 * @uses esc_js()
	 * @uses Request::current();
	 *
	 * @return bool
	 */
	protected function validate_request_method( $allowed ) {
		$allowed = array_map( 'strtoupper', $allowed );
		$method = Request::current()->method;

		if ( ! in_array( $method, $allowed ) ) {
			wp_send_json_error( esc_js( sprintf( __( '%s requests are not allowed', 'medicare' ), $method ) ) );
		}

		return true;
	}
}