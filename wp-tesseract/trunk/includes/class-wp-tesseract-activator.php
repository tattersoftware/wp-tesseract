<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/tattersoftware/wp-tesseract
 * @since      1.0.0
 *
 * @package    WP_Tesseract
 * @subpackage WP_Tesseract/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    WP_Tesseract
 * @subpackage WP_Tesseract/includes
 * @author     Tatter Software <support@tattersoftware.com>
 */
class WP_Tesseract_Activator
{
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
		// Set defaults for all the settings
		add_option('ocr_tesseract_path', '/usr/bin/tesseract');
		add_option('ocr_resize_percent', 200);
		add_option('ocr_language_string', 'eng');
	}
}
