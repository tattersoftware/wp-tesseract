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
		register_setting('ocr-settings-group', 'ocr_imagemagick_path');
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
	 */
	function analyze_image(int $image_id)
	{
		$upload_dir = wp_upload_dir();
		$upload_dir = $upload_dir['basedir'];
		$image_path = $upload_dir . '/' . get_post_meta($image_id, '_wp_attached_file', true);
		
		// Only go through the steps for OCR if the file is an image
		if (getimagesize($image_path))
		{
			$imagemagick     = get_option('ocr_imagemagick_path');
			$tesseract       = get_option('ocr_tesseract_path');
			$size_percent    = get_option('ocr_resize_percent');
			$language_string = get_option('ocr_language_string');

			// Only analyze the image if the plugin configuration has been filled in
			if ($imagemagick && $tesseract && $size_percent)
			{
				$temp_image = $upload_dir . '/ocr_image.tif'; // Tesseract used to require a tiff
				$temp_text  = $upload_dir . '/ocr_text';
				$command    = $imagemagick . ' -resize ' . $size_percent . '% ' . $image_path . ' ' . $temp_image . ' && ' .
					$tesseract . ' ' . $temp_image . ' ' . $temp_text . ' -l ' . $ocr_language_string . ' && ' .
					'cat ' . $temp_text . '.txt && rm -f ' . $temp_text . '.txt ' . $temp_image;
				
				if ($ocr_text = shell_exec($command))
				{
					wp_insert_post([
						'post_title'   => basename(get_attached_file($image_id)),
						'post_content' => $ocr_text,
						'post_status'  => 'publish',
					]);
				}
				else
				{
					throw new \RuntimeException('No OCR text returned: ' . $command);
				}
			}
		}
	}
}
