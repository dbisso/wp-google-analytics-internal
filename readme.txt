=== Plugin Name ===
Contributors: dbisso
Tags: google analytics, events, internal, comments analytics, publish, yoast
Requires at least: 3.8.1
Tested up to: 4.1.1
Stable tag: 0.3.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Use Google Analytics events to track when you publish posts.

== Description ==

Google Analytics gathers lots of data when users interact with your site from the front end, but sometimes you need to match this with actions taken in WordPress.

This plugin triggers a custom event in Google Analytics when a post is published. That way you can monitor any relationship between publishing and changes in traffic.

Update: the plugin now creates events for comments too so you can track the number of comments submitted or approved.

That's it at the moment. If there are other events you would like to track or any improvements, let me know in the support forums or even better on [GitHub](http://github.com/dbisso/wp-google-analytics-internal).


If you already have Yoast's [Google Analytics for WordPress](http://wordpress.org/plugins/google-analytics-for-wordpress/) installed and configured, you don't need to do anything as the plugin should find you UA string automatically.

If you don't have Yoast's plugin, you can set your UA string in your `wp-config.php`:

`define( 'DBISSO_GA_UA', 'UA-XXXXXXXX-Y' );`

== Installation ==


1. Upload `dbisso-google-analytics-internal` foler to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

If you already have Yoast's [Google Analytics for WordPress](http://wordpress.org/plugins/google-analytics-for-wordpress/) installed and configured, you don't need to do anything as the plugin should find you UA string automatically.

If you don't have Yoast's plugin, you can set your UA string in your `wp-config.php`:

`define( 'DBISSO_GA_UA', 'UA-XXXXXXXX-Y' );`

== Configuration ==

Currently there is no GUI for the plugin, but you can use the `dbisso_gai_event_actions` filter to choose which events you want to be triggered and what the Google Analytics event action will be. For example:

~~~
add_filter( 'dbisso_gai_event_actions', 'custom_theme_filter_dbisso_gai_event_actions', 10, 1 );

function custom_theme_filter_dbisso_gai_event_actions( $actions ) {
	// Don't send 'update post' events
	$actions['update_post'] = false;

	// Change the action name that appears in Google Analytics
	$actions['publish_post'] = __( 'Publish Post' ),

	return $actions
}
~~~

There are currently four actions `publish_post`, `update_posts`, `comment_submitted` and `comment_approved`.


== Changelog ==

= 0.2.0 =
* Feature: Add tracking when a comment is posted or approved.
* Introduce filter `dbisso_gai_event_data` to filter data just before event is sent.
* Introduce filter `dbisso_gai_event_actions` to set the action strings for different WP.
* Updates to posts are now have a separate action ('Update Post').
* I18n for some strings.
* Introduce `DBisso_GoogleAnalyticsInternal_Event` to manage the sending of events.
* Add some basic unit tests
* Include my name in copyright statement!

= 0.1.0 =
* Initial release