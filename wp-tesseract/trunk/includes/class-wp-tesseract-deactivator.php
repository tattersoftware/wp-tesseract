<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://github.com/tattersoftware/wp-tesseract
 * @since      1.0.0
 *
 * @package    WP_Tesseract
 * @subpackage WP_Tesseract/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    WP_Tesseract
 * @subpackage WP_Tesseract/includes
 * @author     Tatter Software <support@tattersoftware.com>
 */
class WP_Tesseract_Deactivator
{
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		delete_option('ocr_tesseract_path');
		delete_option('ocr_resize_percent');
		delete_option('ocr_language_string');
	}
}
