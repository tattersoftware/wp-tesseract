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
		// Set the default path to ImageMagick
		add_option('ocr_imagemagick_path', '/usr/local/bin/magick');

		// Set the default path to Tesseract
		add_option('ocr_tesseract_path', '/usr/bin/tesseract');

		// Set the default value for the resize percent
		add_option('ocr_resize_percent', 200);
	}
}
