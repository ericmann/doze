<?php
namespace TenUp\Doze\v1_0_0;

use TenUp\HTTP\v1_0_0 as HTTP;
use WP_Mock as M;

/**
 * Class AuthTest
 *
 * @package Doze
 *
 * @group auth
 */
class APITest extends TestCase {

	protected $testFiles = array(
		'API.php',
	);

	public function setUp() {
		parent::setUp();

		// Since we're testing headers, explicitly clear them between tests.
		new HTTP\Header();
		HTTP\Header\clear();
	}

	/**
	 * @runInSeparateProcess
	 */
	public function test_cors_sends_headers() {
		$request = \Mockery::mock( 'stdClass' )
			->shouldReceive( 'globals' )
			->andReturn( '' );
		\Mockery::mock( 'alias:' . __NAMESPACE__ . '\\Request' )
			->shouldReceive( 'current' )
			->andReturn( $request->getMock() );

		// Set up our API object such that the do_cors() function is accessible
		$api = new API();
		$api_class = new \ReflectionClass( $api );
		$do_cors = $api_class->getMethod( 'do_cors' );
		$do_cors->setAccessible( true );

		$do_cors->invoke( $api );

		$headers = HTTP\Header\get();

		// Verify headers
		$this->assertNotEmpty( $headers );
		$this->assertEquals( 4, count( $headers ) );
	}

	/**
	 * @runInSeparateProcess
	 */
	public function test_cors_sends_allow_headers() {
		$request = new \stdClass;
		$request->globals = function( $key ) {
			return 'header';
		};
		\Mockery::mock( 'alias:' . __NAMESPACE__ . '\\Request' )
		        ->shouldReceive( 'current' )
		        ->andReturn( $request );

		$this->markTestIncomplete();
	}

	/**
	 * @runInSeparateProcess
	 */
	public function test_validate_request_method_allows_whitelisted_methods() {
		// Set up our API object such that the validate_request_method() function is accessible
		$api = new API();
		$api_class = new \ReflectionClass( $api );
		$validate_request_method = $api_class->getMethod( 'validate_request_method' );
		$validate_request_method->setAccessible( true );

		// Mock the request Object
		$post = new \stdClass;
		$post->method = 'POST';
		$get = new \stdClass;
		$get->method = 'GET';
		$put = new \stdClass;
		$put->method = 'PUT';
		$delete = new \stdClass;
		$delete->method = 'DELETE';
		$options = new \stdClass;
		$options->method = 'OPTIONS';
		$request = \Mockery::mock( 'alias:' . __NAMESPACE__ . '\\Request' )
			->shouldReceive( 'current' )
			->andReturn( $post, $get, $put, $delete, $options );

		// Test POST
		$pass = $validate_request_method->invokeArgs( $api, array( array( 'post')  ) );
		$this->assertTrue( $pass );

		// Test GET
		$pass = $validate_request_method->invokeArgs( $api, array( array( 'get' ) ) );
		$this->assertTrue( $pass );

		// Test PUT
		$pass = $validate_request_method->invokeArgs( $api, array( array( 'put' ) ) );
		$this->assertTrue( $pass );

		// Test DELETE
		$pass = $validate_request_method->invokeArgs( $api, array( array( 'delete' ) ) );
		$this->assertTrue( $pass );

		// Test OPTIONS
		$pass = $validate_request_method->invokeArgs( $api, array( array( 'options' ) ) );
		$this->assertTrue( $pass );
	}

	/**
	 * @runInSeparateProcess
	 */
	public function test_validate_request_method_disallows_nonwhitelisted_methods() {
		// Set up our API object such that the validate_request_method() function is accessible
		$api = new API();
		$api_class = new \ReflectionClass( $api );
		$validate_request_method = $api_class->getMethod( 'validate_request_method' );
		$validate_request_method->setAccessible( true );

		// Mock the request Object
		$get = new \stdClass;
		$get->method = 'GET';
		$request = \Mockery::mock( 'alias:' . __NAMESPACE__ . '\\Request' )
		                   ->shouldReceive( 'current' )
		                   ->andReturn( $get );

		// Since we're expecting the requests to fail, we need to mock our failure conditions
		M::wpPassthruFunction( 'esc_js' );
		M::wpPassthruFunction( '__' );
		M::wpFunction( 'wp_send_json_error', array(
			'times' => 4,
			'args' => array( '*' ),
		) );

		// Test POST
		$pass = $validate_request_method->invokeArgs( $api, array( array( 'post')  ) );

		// Test PUT
		$pass = $validate_request_method->invokeArgs( $api, array( array( 'put' ) ) );

		// Test DELETE
		$pass = $validate_request_method->invokeArgs( $api, array( array( 'delete' ) ) );

		// Test OPTIONS
		$pass = $validate_request_method->invokeArgs( $api, array( array( 'options' ) ) );

		$this->assertConditionsMet();
	}
}