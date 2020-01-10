<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/tattersoftware/wp-tesseract
 * @since      1.0.0
 *
 * @package    WP_Tesseract
 * @subpackage WP_Tesseract/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WP_Tesseract
 * @subpackage WP_Tesseract/admin
 * @author     Tatter Software <support@tattersoftware.com>
 */
class WP_Tesseract_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Add the admin page under Settings
	 */
	public function add_menu()
	{
		add_submenu_page(
			'options-general.php',
			'Tesseract Configuration',
			'Tesseract',
			'administrator',
			__FILE__,
			[$this, 'page_options']
		);
	}

	/**
	 * Register each setting to the group
	 */
	public function register_settings()
	{
		register_setting('ocr-settings-group', 'ocr_tesseract_path');
		register_setting('ocr-settings-group', 'ocr_resize_percent');
		register_setting('ocr-settings-group', 'ocr_language_string');
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WP_Tesseract_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WP_Tesseract_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-tesseract-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WP_Tesseract_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WP_Tesseract_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-tesseract-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Creates the options page
	 *
	 * @since 		1.0.0
	 * @return 		void
	 */
	public function page_options() {
		include( plugin_dir_path( __FILE__ ) . 'partials/wp-tesseract-admin-display.php' );
	}

	/**
	 * Perform the image conversion and OCR analysis
	 *
	 * @param int  $image_id  ID of the image to analyze
	 *
	 * @return bool Success or failure
	 */
	function analyze_image(int $image_id): bool
	{
		// Verify the tools
		if (! extension_loaded('gd'))
		{
			return false;
		}

		$tesseract = get_option('ocr_tesseract_path');
		if (empty($tesseract) || ! is_executable($tesseract))
		{
			return false;
		}

		// Validate the settings
		$size_percent = get_option('ocr_resize_percent');
		if (empty($size_percent) || ! is_numeric($size_percent))
		{
			return false;
		}

		if (! $language_string = get_option('ocr_language_string'))
		{
			return false;
		}

		// Find the uploaded file
		$upload_dir = wp_upload_dir();
		$upload_dir = $upload_dir['basedir'];
		$image_path = $upload_dir . '/' . get_post_meta($image_id, '_wp_attached_file', true);

		// Veriy the file is actually an image
		$size = getimagesize($image_path);
		if (! is_array($size) || count($size) < 3)
		{
			return false;
		}
		$width  = ($size[0] * $size_percent) / 100;
		$height = ($size[1] * $size_percent) / 100;

		// Use the correct source format
		switch ($size[2])
		{
			case IMAGETYPE_BMP:      $function = 'imagecreatefrombmp'; break;
			case IMAGETYPE_GIF:      $function = 'imagecreatefromgif'; break;
			case IMAGETYPE_JPEG:     $function = 'imagecreatefromjpeg'; break;
			case IMAGETYPE_JPEG2000: $function = 'imagecreatefromjpeg'; break;
			case IMAGETYPE_PNG:      $function = 'imagecreatefrompng'; break;
			case IMAGETYPE_WBMP:     $function = 'imagecreatefromwbmp'; break;
			case IMAGETYPE_WEBP:     $function = 'imagecreatefromwebp'; break;
			case IMAGETYPE_XBM:      $function = 'imagecreatefromxmb'; break;
			default:
				return false;
		}

		$source = @$function($image_path);

		// Make sure GD was able to read it
		if (! $source)
		{
			return false;
		}

		// Create the destination
		$destination = imagecreatetruecolor($width, $height);

		// Resample from the source
		$result = imagecopyresampled($destination, $source, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
		if (! $result)
		{
			return false;
		}

		// Create the new file
		$temp_image = $upload_dir . '/ocr_image.png';
		$result = imagepng($destination, $temp_image, 0);
		if (! $result)
		{
			return false;
		}

		// Define a text destination
		$temp_text  = $upload_dir . '/ocr_text';

		// Run Tesseract against the image
		$command = $tesseract . ' ' . $temp_image . ' ' . $temp_text . ' -l ' . $language_string;
		exec($command, $output, $return);

		// Remove the scaled image
		unlink($temp_image);

		// Verify the command succeeded
		if ($return !== 0)
		{
			return false;
		}

		// Get the output and remove the temp file
		$ocr_text = file_get_contents($temp_text . '.txt');
		unlink($temp_text . '.txt');

		// Make sure there was some text detected
		if (empty($ocr_text))
		{
			return false;
		}

		// Create a new post with the filename as title and OCR as content
		$data = [
			'post_title'   => basename(get_attached_file($image_id)),
			'post_content' => $ocr_text,
			'post_status'  => 'publish',
		];

		// Try the insert
		if ($postId = wp_insert_post($data))
		{
			// Set the uploaded file as the featured image
			add_post_meta($postId, '_thumbnail_id', $image_id, true);
		}

		return true;
	}
}
