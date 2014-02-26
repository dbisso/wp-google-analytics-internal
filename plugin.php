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
	function plugins_loaded() {
		load_plugin_textdomain( 'dbisso-google-analytics-internal', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Trigger the GA event when a post is published
	 * @param  int $post_id The post ID.
	 */
	static public function action_publish_post( $post_id ) {
		$event = new DBisso_GoogleAnalyticsInternal_Event( __( 'Publish Post', 'dbisso-google-analytics-internal' ), get_the_title( (int) $post_id ) );
		$event->send();
	}
}

// Start the plugin.
DBisso_GoogleAnalyticsInternal::bootstrap();