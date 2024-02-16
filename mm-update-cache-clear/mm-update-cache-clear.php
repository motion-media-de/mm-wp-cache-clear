<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://motion-media.de
 * @since             1.0.0
 * @package           Mm_Update_Cache_Clear
 *
 * @wordpress-plugin
 * Plugin Name:       Clear Cache On Update for WP-Rocket
 * Plugin URI:        https://motion-media.de
 * Description:       Clears the WP-Rocket after when Wordpress or plugins get updated 
 * Version:           1.0.0
 * Author:            Motion Media
 * Author URI:        https://motion-media.de/
 * License:           MIT
 * License URI:       https://opensource.org/license/mit/
 * Text Domain:       mm-update-cache-clear
 * Domain Path:       /
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'MM_UPDATE_CACHE_CLEAR_VERSION', '1.0.0' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mm-update-cache-clear.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_mm_update_cache_clear() {

	$plugin = new Mm_Update_Cache_Clear();
	$plugin->run();

}
run_mm_update_cache_clear();
