<?php
/*
Plugin Name: Google Analytics Internal
Plugin URI: http://danisadesigner.com/plugins/google-analytics-internal
Description: Use Google Analytics events to track when you publish posts.
Version: 0.2.0
Author: Dan Bissonnet
Author URI: http://danisadesigner.com/
Text Domain: dbisso-google-analytics-internal
*/

/**
 * Copyright (c) 2014 Dan Bissonnet. All rights reserved.
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

include_once 'lib/DBisso_GoogleAnalyticsInternal_Event.php';
/**
 * Triggers Google Analytics measurement when internal WordPress events
 * occur.
 */
class DBisso_GoogleAnalyticsInternal {
	const OPTION = 'dbisso_gai_options';
	/**
	 * Set up and bind hooks.
	 */
	static public function bootstrap() {
		add_action( 'publish_post', array( __CLASS__, 'action_publish_post' ), 10, 1 );
		add_action( 'plugins_loaded', array( __CLASS__, 'plugins_loaded' ) );
	}

	/**
	 * Load our text domain
	 */
	static public function plugins_loaded() {
		load_plugin_textdomain( 'dbisso-google-analytics-internal', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Trigger the GA event when a post is published
	 * @param  int $post_id The post ID.
	 */
	static public function action_publish_post( $post_id ) {
		$separate_update_events = self::get_option( 'separate_update_events' );
		$is_post_published = self::is_post_published( $post_id );

		if ( $separate_update_events && $is_post_published ) {
			$action = self::get_event_action( 'update_post' );
		} else {
			$action = self::get_event_action( 'publish_post' );
		}

		if ( is_string( $action ) ) {
			$event = new DBisso_GoogleAnalyticsInternal_Event(
				$action,
				get_the_title( (int) $post_id )
			);

			$event->send();
		}
	}

	/**
	 * Get plugins options
	 */
	static private function get_options() {
		$data = get_option( self::OPTION, self::get_options_defaults() );

		return $data;
	}

	static private function get_option( $name ) {
		$options = self::get_options();

		return $options[$name];
	}

	static private function get_options_defaults() {
		return array(
			'separate_update_events' => true,
		);
	}

	/**
	 * Get the action string for a given WP event
	 * @param  string $hook The name of the hook.
	 * @return string       The action for the hook.
	 */
	static private function get_event_action( $hook ) {
		$actions = self::get_event_actions();

		return $actions[$hook];
	}

	/**
	 * Returns the filtered string to use as the action in the GA event.
	 * @return array Array with WP event => action text.
	 */
	static private function get_event_actions() {
		$actions = array(
			'publish_post' => __( 'Publish Post', 'dbisso-google-analytics-internal' ),
			'update_post' => __( 'Update Post', 'dbisso-google-analytics-internal' ),
			'comment_post' => __( 'Comment Submitted', 'dbisso-google-analytics-internal' ),
			'comment_approved' => __( 'Comment Approved', 'dbisso-google-analytics-internal' )
		);

		return apply_filters( 'dbisso_gai_event_actions', $actions );
	}

	static private function is_post_published( $post_id ) {
		return get_post_status( $post_id ) === 'publish';
	}
}

// Start the plugin.
DBisso_GoogleAnalyticsInternal::bootstrap();