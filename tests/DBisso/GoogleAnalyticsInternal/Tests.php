<?php
use DBisso_GoogleAnalyticsInternal as Plugin;

require_once 'TestCase.php';
require_once '../lib/DBisso_GoogleAnalyticsInternal.php';
require_once '../lib/DBisso_GoogleAnalyticsInternal_Event.php';

class DBisso_GoogleAnalyticsInternal_Tests extends DBisso_GoogleAnalyticsInternal_TestCase {
	protected $plugin;

	public function setUp() {
		parent::setUp();
		$this->maybeDefineUAString();
		Plugin::bootstrap();
	}

	public function testInitialization() {
		$this->assertTrue( class_exists( 'DBisso_GoogleAnalyticsInternal_Event' ), "Plugin class doesn't exist" );
	}

	/**
	 * @covers DBisso_GoogleAnalyticsInternal::plugins_loaded
	 * @todo   Implement testPlugins_loaded().
	 */
	// public function testPlugins_loaded() {
	// 	// Remove the following lines when you implement this test.
	// 	$this->markTestIncomplete(
	// 		'This test has not been implemented yet.'
	// 	);
	// }

	/**
	 * @dataProvider dataGetPostEventAction
	 */
	public function testGetPostEventAction( $new_status, $old_status, $expected_action ) {
		$action = Plugin::get_post_event_action( $new_status, $old_status );

		$this->assertEquals( $expected_action, $action );
	}

	/**
	 * Data Provider for testGetPostEventAction
	 * @return array
	 */
	public function dataGetPostEventAction() {
		return array(
			array( 'publish', 'draft', 'Publish Post' ),
			array( 'publish', 'publish', 'Update Post' ),
			array( 'draft', 'draft', false ),
		);
	}

	/**
	 * @covers DBisso_GoogleAnalyticsInternal::action_publish_post
	 */
	public function testPublishedDraftTriggersPublishEvent() {
		$post_id    = 1;
		$post_title = get_the_title( 1 );

		// Make sure we have a draft
		wp_update_post( array( 'ID' => 1, 'post_status' => 'draft' ) );
		$this->assertEquals( get_post_status( 1 ), 'draft' );

		$this->http_spy();

		wp_update_post( array( 'ID' => 1, 'post_status' => 'publish' ) );

		$request_body = $this->http_spy_get_request_body();
		$this->http_spy_clean();

		$this->assertTrue( is_array( $request_body ), 'HTTP Request body is not an array' );
		$this->assertArrayHasKey( 'el', $request_body, 'The request body has no label key ("el")' );
		$this->assertArrayHasKey( 'ea', $request_body, 'The request body has no aciton key ("ea")' );

		$this->assertEquals( $request_body['el'], $post_title, 'The post title was not sent as the event label' );
		$this->assertEquals( $request_body['ea'], 'Publish Post', '"Publish Post" was not set as the event action' );
	}

	public function testUpdatedPostTriggersUpdateEvent() {
		$this->assertEquals( get_post_status( 1 ), 'publish' );

		$this->http_spy();

		// Set the post to publish
		$post_id = wp_update_post( array( 'ID' => 1, 'post_status' => 'publish' ) );

		$this->assertEquals( 1, $post_id );

		$request_body = $this->http_spy_get_request_body();
		$this->http_spy_clean();

		$this->assertTrue( is_array( $request_body ), 'HTTP Request body is not an array' );

		$this->assertArrayHasKey( 'ea', $request_body, 'The event request body has no action' );
		$this->assertArrayHasKey( 'el', $request_body, 'The event request body has no label' );

		$this->assertEquals( $request_body['ea'], 'Update Post', '"Update Post" was not set as the event action' );
	}

	/**
	 * @covers DBisso_GoogleAnalyticsInternal::action_comment_post
	 * @todo   Implement testAction_comment_post().
	 */
	public function testAction_comment_post() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}
}