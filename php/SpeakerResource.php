<?php
namespace TenUp\Doze\v1_0_0;

class Speaker extends Resource {

	/**
	 * Process the actual resource
	 *
	 * @param string|null $item_id Item being acted upon by the API.
	 *
	 * @return mixed
	 */
	public function process( $item_id = null ) {
		if ( null === $item_id ) {
			// No speaker ID is passed in, so we need to print out a JSON array of speakers instead.
			$output = get_speakers( 10 );
		} else {
			// We have a speaker ID, so let's fetch that specific speaker
			$output = get_speaker( $item_id );
		}

		// We're sending JSON data through a JSON method, so no need to set headers manually.
		wp_send_json( $output );
	}
}