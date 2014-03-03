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

	public function dataCommentStatus() {
		return array(
			array( 'spam', null ),
			array( 0, 'Comment Submitted' ),
			array( 1, 'Comment Approved' ),
		);
	}

	/**
	 * @dataProvider dataCommentStatus
	 * @param  string|int $status      Comment status
	 * @param  string $expected_action The event action
	 */
	public function testActionCommentPost( $status, $expected_action ) {
		$action = Plugin::get_comment_event_action( $status );
		$this->assertEquals( $expected_action, $action );
	}

	/**
     * Test the triggering of events when a comment is posted and approved
	 */
	public function testActionCommentApproved() {
		$post_id = 1;
		$post = get_post( $post_id );
		$time = current_time( 'mysql' );

		$data = array(
			'comment_post_ID' => $post_id,
			'comment_author' => 'admin',
			'comment_author_email' => 'admin@admin.com',
			'comment_author_url' => 'http://',
			'comment_content' => 'content here',
			'comment_type' => '',
			'comment_parent' => 0,
			'user_id' => 1,
			'comment_author_IP' => '127.0.0.1',
			'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
			'comment_date' => $time,
			'comment_approved' => 1,
		);

		$this->http_spy();
		wp_insert_comment( $data );

		$request_body = $this->http_spy_get_request_body();

		$this->assertTrue( is_array( $request_body ), 'HTTP Request body is not an array' );

		$this->assertArrayHasKey( 'ea', $request_body, 'The event request body has no action' );
		$this->assertArrayHasKey( 'el', $request_body, 'The event request body has no label' );

		$this->assertEquals( $request_body['ea'], 'Comment Approved', '"Comment Approved" was not set as the event action' );
		$this->assertEquals( $request_body['el'], get_the_title( $post_id ), 'Post title was not set as the event label' );
	}
}