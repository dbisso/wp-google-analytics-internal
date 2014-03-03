<?php

class DBisso_GoogleAnalyticsInternal_TestCase extends PHPUnit_Framework_TestCase {
	public $http_spy_content = array();

	protected function maybeDefineUAString() {
		if ( !defined( 'DBISSO_GA_UA' ) ) {
			$ua_string = 'UA-000000-XX';
			define( 'DBISSO_GA_UA', $ua_string );
		}

		return DBISSO_GA_UA;
	}

	protected function assertGAIRequestBodyIsValid( $request_body ) {
		$this->assertTrue( is_array( $request_body ), 'HTTP Request body is not an array' );
		$this->assertArrayHasKey( 'ea', $request_body, 'The event request body has no action' );
		$this->assertArrayHasKey( 'el', $request_body, 'The event request body has no label' );
	}

	protected function assertGAIRequestBodyIsNotValid( $request_body ) {
		$this->assertFalse( is_array( $request_body ), 'HTTP Request body is not an array' );
	}

	protected function http_spy( $return = true ) {
		add_filter(
			'pre_http_request',
			$this->pre_http_request_spy( $return ),
			10,
			3
		);
	}

	public function pre_http_request_spy( $return ) {
		$http_spy_content =& $this->http_spy_content;

		return function ( $false, $request, $url ) use ( &$http_spy_content, $return ) {
			$http_spy_content[] = array(
				'headers' =>
					array(
						'pragma' => 'no-cache',
						'expires' => 'Mon, 07 Aug 1995 23:30:00 GMT',
						'cache-control' => 'private, no-cache, no-cache=Set-Cookie, proxy-revalidate',
						'access-control-allow-origin' => '*',
						'last-modified' => 'Sun, 17 May 1998 03:00:00 GMT',
						'x-content-type-options' => 'nosniff',
						'content-type' => 'image/gif',
						'date' => 'Sat, 01 Mar 2014 17:19:00 GMT',
						'server' => 'Golfe2',
						'content-length' => '35',
						'alternate-protocol' => '443:quic',
						'x-wordpress-mock-api' => '1',
				),
				'body' => 'GIF89a' . "\0" . '' . "\0" . '??' . "\0" . '???' . "\0" . '' . "\0" . '' . "\0" . ',' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . 'D' . "\0" . ';',
				'response' =>
					array(
						'code' => 200,
						'message' => 'OK',
					),
				'cookies' => array(),
				'filename' => NULL,
				'request' => $request,
				'url' => $url,
			);

			return $return;
		};
	}

	protected function http_spy_get_request() {
		return $this->http_spy_get_last_request();
	}

	protected function http_spy_get_request_body() {
		$last_request = $this->http_spy_get_last_request();
		return $last_request['body'];
	}

	protected function http_spy_clean() {
		$this->http_spy_content = null;
	}

	protected function http_spy_get_clean() {
		$value = $this->http_spy_content;
		$this->http_spy_clean();

		return $value;
	}

	protected function http_spy_get_requests() {
		$requests = array();

		foreach ( $this->http_spy_content as $content ) {
			$requests[] = $content['request'];
		}

		return $requests;
	}

	protected function http_spy_get_last_request() {
		if ( count( $this->http_spy_content ) > 0 ) {
			return $this->http_spy_content[count( $this->http_spy_content ) - 1]['request'];
		}
	}
}