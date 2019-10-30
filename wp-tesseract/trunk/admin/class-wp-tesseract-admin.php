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

		// Add the submenu
		add_action('admin_menu', [$this, 'addSettingsPage']);
	}

	/**
	 * Add the admin page under Settings
	 */
	public function addSettingsPage()
	{
		add_submenu_page('options-general.php', 'Tesseract Configuration', 'Tesseract', 'administrator', __FILE__, [$this, 'settingsPageHtml']);
		add_action('admin_init', [$this, 'registerSettings']);
	}
	
	/**
	 * Register each setting to the group
	 */
	public function registerSettings()
	{
		register_setting('ocr-settings-group', 'ocr_imagemagick_path');
		register_setting('ocr-settings-group', 'ocr_tesseract_path');
		register_setting('ocr-settings-group', 'ocr_resize_percent');
	}
	
	/**
	 * The actual HTML for the settings page form
	 */
	public function settingsPageHtml()
	{
?>
		<div class="wrap">
			<h2>Tesseract Settings</h2>
			<p>
				The Tesseract plugin requires two command line utilities:
				<a target="_blank" href="https://www.imagemagick.org">ImageMagick</a> for preparing the images and
				<a target="_blank" href="https://github.com/tesseract-ocr/">Tesseract</a> for the actual OCR.
				These utilities must be manually installed on your server and executable by PHP.
				<strong>This process, and consequently this plugin, is recommended only for advanced users.</strong>
			</p>
			
			<form method="post" action="options.php">

				<?php settings_fields('ocr-settings-group'); ?>

				<table class="form-table">
					<tr valign="top">
						<th scope="row">Absolute Path to <a target="_blank" href="https://www.imagemagick.org">ImageMagick</a><br/><i style="font-size:10px;">(ex: /usr/local/bin/magick)</i></th>
						<td><input type="text" name="ocr_imagemagick_path" value="<?= get_option('ocr_imagemagick_path'); ?>" /></td>
					</tr>
					<tr valign="top">
						<th scope="row">Absolute Path to <a target="_blank" href="https://github.com/tesseract-ocr/">Tesseract</a><br><i style="font-size:10px;">(ex: /usr/bin/tesseract)</i></th>
						<td><input type="text" name="ocr_tesseract_path" value="<?= get_option('ocr_tesseract_path'); ?>" /></td>
					</tr>
					<tr valign="top">
						<th scope="row">Resize percentage<br><i style="font-size:10px;">A higher % might lead to more accurate OCR but will take longer to calculate. Default = 200%</i></th>
						<td><input type="text" name="ocr_resize_percent" value="<?= get_option('ocr_resize_percent'); ?>" />%</td>
					</tr>
				</table>
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
				</p>
			</form>
		</div>
<?php
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

}
