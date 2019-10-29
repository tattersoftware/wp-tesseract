<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/tattersoftware/wp-tesseract
 * @since      1.0.0
 *
 * @package    WP_Tesseract
 * @subpackage WP_Tesseract/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    WP_Tesseract
 * @subpackage WP_Tesseract/includes
 * @author     Tatter Software <support@tattersoftware.com>
 */
class WP_Tesseract_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-tesseract',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
