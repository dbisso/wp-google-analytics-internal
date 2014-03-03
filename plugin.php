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

include_once 'lib/DBisso_GoogleAnalyticsInternal.php';

// Start the plugin.
DBisso_GoogleAnalyticsInternal::bootstrap();