<?php

class DBisso_GoogleAnalyticsInternal_TestCase extends PHPUnit_Framework_TestCase {
	public $http_spy_content;

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
			$http_spy_content = array(
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
		return $this->http_spy_content['request'];
	}

	protected function http_spy_get_request_body() {
		return $this->http_spy_content['request']['body'];
	}

	protected function http_spy_clean() {
		$this->http_spy_content = null;
	}

	protected function http_spy_get_clean() {
		$value = $this->http_spy_content;
		$this->http_spy_clean();

		return $value;
	}


}