<?php
namespace TenUp\Doze\v1_0_0;

/**
 * Base class for new API resources
 *
 * @package Doze
 */
abstract class Resource {

	/**
	 * @var string Resource tag
	 */
	public $slug = 'resource';

	/**
	 * Default constructor. Adds the resource to the system and wires the
	 *
	 * @uses add_filter()
	 */
	public function __construct() {
		add_filter( 'doze_resources', function( $resources ) {
			$resources[] = $this->slug;

			return $resources;
		} );

		add_action( 'doze-api-' . $this->slug, array( $this, 'process' ), 10, 1 );
	}

	/**
	 * Process the actual resource
	 *
	 * @param string|null $item_id Item being acted upon by the API.
	 *
	 * @return mixed
	 */
	abstract public function process( $item_id = null );
}