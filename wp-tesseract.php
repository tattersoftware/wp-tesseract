<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/tattersoftware/wp-tesseract
 * @since             1.0.0
 * @package           WP_Tesseract
 *
 * @wordpress-plugin
 * Plugin Name:       WP-Tesseract
 * Plugin URI:        https://github.com/tattersoftware/wp-tesseract
 * Description:       Add Tesseract's OCR (image-to-text) functionality to WordPress. Requires pre-installed software.
 * Version:           1.0.2
 * Author:            Tatter Software
 * Author URI:        https://tattersoftware.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-tesseract
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) )
{
	die;
}

/**
 * Current plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WP_TESSERACT_VERSION', '1.0.4' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-tesseract-activator.php
 */
function activate_wp_tesseract()
{
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-tesseract-activator.php';
	WP_Tesseract_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-tesseract-deactivator.php
 */
function deactivate_wp_tesseract()
{
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-tesseract-deactivator.php';
	WP_Tesseract_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_tesseract' );
register_deactivation_hook( __FILE__, 'deactivate_wp_tesseract' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-tesseract.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_tesseract()
{
	$plugin = new WP_Tesseract();
	$plugin->run();

}

run_wp_tesseract();
