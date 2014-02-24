<?php

/*
Plugin Name: Google Analytics Internal
Plugin URI: http://danisadesigner.com/plugins/google-analytics-internal
Description: Use Google Analytics events to track when you publish posts.
Version: 0.1.0
Author: Dan Bissonnet
Author URI: http://danisadesigner.com/
*/

/**
 * Copyright (c) 2014 Your Name. All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * **********************************************************************
 */

/**
 * Triggers Google Analytics measurement when internal WordPress events
 * occur.
 */
class DBisso_GoogleAnalyticsInternal {
	static $ga_endpoint = 'https://ssl.google-analytics.com/collect';

	/**
	 * Set up and bind hooks.
	 */
	static public function bootstrap() {
		add_action( 'publish_post', array( __CLASS__, 'action_publish_post' ), 10, 99 );
	}

	/**
	 * Trigger the GA event when a post is published
	 * @param  int $post_id The post ID.
	 */
	static public function action_publish_post( $post_id ) {
		$post_args = self::get_post_data_event( 'Publish Post', get_the_title( (int) $post_id ) );

		// No UA string found so bail out
		if ( empty( $post_args['tid'] ) ) {
			return;
		}

		$result = wp_remote_post(
			self::$ga_endpoint,
			array( 'body' => $post_args )
		);
	}

	/**
	 * Get POST data for triggering an event
	 * @param  string  $action The event action.
	 * @param  string $label   The event label.
	 * @param  int $value      Value to assign to the event.
	 * @return array           The data to send with the POST request
	 */
	private static function get_post_data_event( $action, $label = false, $value = false ) {
		$data = self::get_post_data();

		$data['t']  = 'event';
		$data['ea'] = $action;

		if ( $label ) {
			$data['el'] = $label;
		}

		if ( $value ) {
			$data['el'] = $label;
		}

		return $data;
	}

	/**
	 * Get the default POST data for a measurement protocol request.
	 * @return array The data for the POST request
	 */
	private static function get_post_data() {
		$user = wp_get_current_user();
		$ua   = self::get_analytics_ua();

		$data = array(
			'v' => 1,
			'tid' => $ua,
			'cid' => $user->id,
			't' => 'event',
			'ec' => 'WordPress',
		);

		return $data;
	}

	/**
	 * Get the Google Analytics UA string.
	 *
	 * Use string from Yoast's Google Analytics is installed. String
	 * can be overridden with a global constant.
	 *
	 * @return string|boolean The UA string to use or false if none found.
	 */
	private static function get_analytics_ua() {
		$yoast_config = get_option( 'Yoast_Google_Analytics' );
		$ua           = false;

		if ( $yoast_config && ! empty( $yoast_config['uastring'] ) ) {
			$ua = $yoast_config['uastring'];
		}

		if ( defined( 'DBISSO_GA_UA' )  ) {
			$ua = DBISSO_GA_UA;
		}

		return $ua;
	}
}

// Start the plugin.
DBisso_GoogleAnalyticsInternal::bootstrap();