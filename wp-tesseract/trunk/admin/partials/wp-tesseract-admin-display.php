<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/tattersoftware/wp-tesseract
 * @since      1.0.0
 *
 * @package    WP_Tesseract
 * @subpackage WP_Tesseract/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

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
				<th scope="row">Absolute Path to <a target="_blank" href="https://www.imagemagick.org">ImageMagick</a> `convert` command<br/><i style="font-size:10px;">(ex: /usr/local/bin/convert)</i></th>
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
			<tr valign="top">
				<th scope="row">Language string<br><i style="font-size:10px;">(ex: eng, or eng+heb)</i></th>
				<td><input type="text" name="ocr_language_string" value="<?= get_option('ocr_language_string'); ?>" /></td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
	</form>
</div>
