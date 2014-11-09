<?php
namespace TenUp\Doze\v1_0_0;

use TenUp\HTTP\v1_0_0 as HTTP;
use WP_Mock as M;

class SpeakerResourceTest extends TestCase {
	protected $testFiles = array(
		'Resource.php',
		'SpeakerResource.php',
	);

	public function setUp() {
		parent::setUp();

		// Since we're testing headers, explicitly clear them between tests.
		new HTTP\Header();
		HTTP\Header\clear();
	}

	/**
	 * When the ::process() method is called with no speaker ID, it should return an array of speakers.
	 *
	 * @runInSeparateProcess
	 */
	public function test_process_returns_array() {
		$this->markTestIncomplete();
	}

	/**
	 * When the ::process() method is called with a valid speaker ID, it should return a speaker.
	 *
	 * @runInSeparateProcess
	 */
	public function test_process_returns_single_speaker() {
		$this->markTestIncomplete();
	}

	/**
	 * When the ::process() method is called with an invalid speaker ID, it should return an empty array.
	 *
	 * @runInSeparateProcess
	 */
	public function test_process_returns_empty_array_on_error() {
		$this->markTestIncomplete();
	}
}