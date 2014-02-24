=== Plugin Name ===
Contributors: dbisso
Tags: google analytics, events, internal, publish, yoast
Requires at least: 3.8.1
Tested up to: 3.9
Stable tag: 0.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Use Google Analytics events to track when you publish posts.

== Description ==

Google Analytics gathers lots of data when users interact with your site from the front end, but sometimes you need to match this with actions taken in WordPress.

This plugin very simply triggers a custom event in Google Analytics when a post is published. That way you can monitor any relationship between publishing and changes in traffic.

That's it at the moment. If there are other event you would like to track, let me know in the support forums or even better on [GitHub](http://github.com/dbisso/wp-google-analytics-internal)

If you already have Yoast's [Google Analytics for WordPress](http://wordpress.org/plugins/google-analytics-for-wordpress/) installed and configured, you don't need to do anything as the plugin should find you UA string automatically.

If you don't have Yoast's plugin, you can set your UA string in your `wp-config.php`:

`define( 'DBISSO_GA_UA', 'UA-XXXXXXXX-Y' );`


== Installation ==


1. Upload `dbisso-google-analytics-internal` foler to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

If you already have Yoast's [Google Analytics for WordPress](http://wordpress.org/plugins/google-analytics-for-wordpress/) installed and configured, you don't need to do anything as the plugin should find you UA string automatically.

If you don't have Yoast's plugin, you can set your UA string in your `wp-config.php`:

`define( 'DBISSO_GA_UA', 'UA-XXXXXXXX-Y' );`


== Changelog ==

= 0.1 =
* Initial release