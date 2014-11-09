<?php
namespace TenUp\Doze\v1_0_0;

/**
 * Abstract base class for data sources within the project.
 *
 * @package TenUp\Doze\v1_0_0
 */
abstract class DataSource {

	/**
	 * Create an object in the data store.
	 *
	 * @param mixed $object
	 *
	 * @return mixed|\WP_Error The newly created object, or an error if something wen't wrong.
	 */
	abstract public function create( $object );

	/**
	 * Get a data object based on its unique ID.
	 *
	 * @param string $id
	 *
	 * @return mixed|null The object if it's found, null if it doesn't exist.
	 */
	abstract public function get_by_id( $id );

	/**
	 * Update the given object in the data store. If the object doesn't already exist (i.e. the ID is not found),
	 * create a new one with a new Id.
	 *
	 * @param mixed $object
	 *
	 * @return mixed|\WP_Error The object as updated in the database (will include a new ID if the object didn't exist).
	 */
	abstract public function update( $object );

	/**
	 * Delete an object from the data store based on its id.
	 *
	 * @param string $id
	 *
	 * @return bool|\WP_Error True on success, WP_Error on failure
	 */
	abstract public function delete( $id );
}