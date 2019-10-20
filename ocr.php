<?php
/**
 *     ---------------       DO NOT DELETE!!!     ---------------
 * 
 *     Plugin Name:  OCR
 *     Plugin URI:   http://formasfunction.com/code/wordpress/ocr
 *     Description:  A plugin for extracting the text from attached images using OCR via tesseract
 *     Version:      0.1.0
 *     Author:       Greg Leppert
 *     Author URI:   http://formasfunction.com
 *
 *     ---------------       DO NOT DELETE!!!     ---------------
 *
 *    This is the required license information for a Wordpress plugin.
 *
 *    Copyright 2009  Greg Leppert  (email : function@formasfunction.com)
 *
 *    This program is free software; you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation; either version 2 of the License, or
 *    (at your option) any later version.
 *
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with this program; if not, write to the Free Software
 *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 *     ---------------       DO NOT DELETE!!!     ---------------
 */

class OCR {
	
	/**
	 * Register the actions, filters, options, uninstaller
	 */
	function __construct()
	{
		if (function_exists('register_uninstall_hook'))
		{
			register_uninstall_hook(__FILE__, [$this, 'Uninstall']);
		}
		
		add_action('add_attachment', [$this, 'AnalyzeImage']);
		add_action('admin_menu',     [$this, 'SubMenuItem']);

		add_filter('attachment_fields_to_edit', [$this, 'EditOCRText'], 10, 2);
		add_filter('attachment_fields_to_save', [$this, 'SaveOCRText'], 10, 2);

		// Set the default value for the resize percent
		add_option('ocr_resize_percent', 200);
	}
	
	/**
	 * Perform the image conversion and OCR analysis
	 *
	 * @param int  $image_id  ID of the image to analyze
	 */
	function AnalyzeImage(int $image_id)
	{
		$upload_dir = wp_upload_dir();
		$upload_dir = $upload_dir['basedir'];
		$image_path = $upload_dir . '/' . get_post_meta($image_id, '_wp_attached_file', true);
		
		// Only go through the steps for OCR if the file is an image
		if (getimagesize($image_path))
		{
			$imagemagick 	= get_option('ocr_imagemagick_path');
			$tesseract		= get_option('ocr_tesseract_path');
			$size_percent	= get_option('ocr_resize_percent');

			// Only analyze the image if the plugin configuration has been filled in
			if ($imagemagick && $tesseract && $size_percent)
			{
				$temp_image = $upload_dir . '/ocr_image.tif'; // Tesseract used to require a tiff
				$temp_text 	= $upload_dir . '/ocr_text';
				$command 	= $imagemagick . ' -resize ' . $size_percent . '% ' . $image_path . ' ' . $temp_image . ' && ' .
					$tesseract . ' ' . $temp_image . ' ' . $temp_text . ' && ' .
					'cat ' . $temp_text . '.txt && rm -f ' . $temp_text . '.txt ' . $temp_image;
				$ocr_text 	= shell_exec($command);
				add_post_meta($image_id, 'ocr_text', $ocr_text, true);
			}
		}
	}

	/**
	 * Add the admin page under Plugins
	 */
	function SubMenuItem()
	{
		add_submenu_page('plugins.php', 'OCR Configuration', 'OCR', 'administrator', __FILE__, [$this, 'SettingsPage']);
		add_action('admin_init', [$this, 'RegisterSettings']);
	}

	/**
	 * Register each setting to the group
	 */
	function RegisterSettings()
	{
		register_setting('ocr-settings-group', 'ocr_imagemagick_path');
		register_setting('ocr-settings-group', 'ocr_tesseract_path');
		register_setting('ocr-settings-group', 'ocr_resize_percent');
	}

	/**
	 * The actual HTML for the settings page form
	 */
	function SettingsPage()
	{
?>
		<div class="wrap">
			<h2>OCR Settings</h2>
			<p>
				The OCR plugin requires PHP5 and two command line utilities:
				<a target="_blank" href="https://www.imagemagick.org">ImageMagick</a> for preparing the images and
				<a target="_blank" href="https://github.com/tesseract-ocr/">Tesseract</a> for the actual OCR.
				These utilities must be manually installed on your server and executable by PHP.
				<strong>This process, and consequently this plugin, is recommended only for advanced users.</strong>
			</p>
			
			<form method="post" action="options.php">
				<?php settings_fields('ocr-settings-group'); ?>
				<table class="form-table">
					<tr valign="top">
						<th scope="row">Absolute Path to <a target="_blank" href="https://www.imagemagick.org">ImageMagick's</a> <a target="_blank" href="https://www.imagemagick.org/script/convert.php">convert</a><br><i style="font-size:10px;">(ex: /opt/local/bin/convert)</i></th>
						<td><input type="text" name="ocr_imagemagick_path" value="<?= get_option('ocr_imagemagick_path'); ?>" /></td>
					</tr>
					<tr valign="top">
						<th scope="row">Absolute Path to <a target="_blank" href="https://github.com/tesseract-ocr/">Tesseract</a><br><i style="font-size:10px;">(ex: /opt/local/bin/tesseract)</i></th>
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
	 * Generate the form field for editing the OCR text from the image
	 */
	function EditOCRText(array $form_fields, string $post): array
	{
		if (substr($post->post_mime_type, 0, 5) == 'image')
		{
			$ocr_text = get_post_meta($post->ID, 'ocr_text', true);
			
			if (empty($ocr_text))
			{
				$ocr_text = '';
			}

			$form_fields['ocr_text'] = [
				'value' => $ocr_text,
				'label' => __('OCR Text'),
				'helps' => __('Text automatically pulled from the image via the OCR plugin.'),
				'input' => 'textarea',
			];
		}
		
		return $form_fields;
	}

	/**
	 * Update the OCR text from the image custom text form field
	 */
	function SaveOCRText(array $post, array $attachment): array
	{
		if (isset($attachment['ocr_text']) && ! empty($attachment['ocr_text']))
		{
			update_post_meta($post['ID'], 'ocr_text', $attachment['ocr_text']);
		}
		
		return $post;
	}

	/**
	 * Remove registered options
	 */
	function Uninstall()
	{
		delete_option('ocr_imagemagick_path');
		delete_option('ocr_tesseract_path');
		delete_option('ocr_resize_percent');
	}
}

if (!$ocr_plugin) { $ocr_plugin = new OCR(); }
