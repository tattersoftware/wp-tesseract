=== OCR ===
Contributors: tattersoftware, formasfunction
Donate link: https://paypal.me/tatter
Tags: ocr, optical text recognition, images, attachments, media, tesseract
Requires at least: 2.9
Tested up to: 5.3.0
Stable tag: 0.1.0
Requires PHP: 7.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A plugin for extracting text from attached images using OCR via Tesseract.

== Description ==

A plugin for extracting text from attached images using [OCR](http://en.wikipedia.org/wiki/Optical_character_recognition) via [Tesseract](https://github.com/tesseract-ocr/).
This plugin adds a field to each image upload named 'OCR Text' containing the recognized text characters within the file.
This text can then be edited for accuracy and added to images to improve search and SEO results.

The OCR plugin requires PHP 7.1 (or later) and the following command line utility:
* [Tesseract](https://github.com/tesseract-ocr) for the actual OCR
This utility must be manually installed on your server and executable by PHP.
**This process, and consequently this plugin, is recommended only for advanced users.**

== Installation ==
1. Install Tesseract OCR on your server ([Tesseract wiki](https://github.com/tesseract-ocr/tesseract/wiki/))
2. Upload `ocr.php` to the `/wp-content/plugins/` directory
3. Activate the plugin through the `Plugins` menu in WordPress
4. Configure the plugin through the `Plugins > OCR` link in the sidebar menu in WordPress

== Frequently Asked Questions ==

= What is Tesseract OCR and where do I get it? =

Tesseract OCR is an open source [optical character recognition](http://en.wikipedia.org/wiki/Optical_character_recognition) library that
the WordPress OCR plugin uses to extract text from images.
The library as well as installation instructions can be found at
[https://github.com/tesseract-ocr/tesseract/wiki/](https://github.com/tesseract-ocr/tesseract/wiki/).

= How do I know if / where I have Tesseract installed on my server? =

Linux:

1. SSH into your server and type `which tesseract`.
2. If Tesseract is installed and in your shell environment PATH the terminal should return a path similar to `/opt/local/bin/tesseract`.
3. Place this path in the configuration of the OCR plugin through the `Plugins > OCR` link in the sidebar menu in WordPress

= Where is the detected text stored? =

The text detected by the OCR plugin is added to the image as a
[custom field](https://wordpress.org/support/article/custom-fields/) named `ocr_text`. See
[https://wordpress.org/support/article/custom-fields/](https://wordpress.org/support/article/custom-fields/)
for instructions on using the `ocr_text` field in your templates.

= Where can I edit the detected text? =

The text detected by the OCR plugin is available in a text area labeled 'OCR Text' both in the 'Add an Image'
model while attaching an image to a post and while editing a previously uploaded image under the 'Media' section of your WordPress install.

= What is the 'Resize percentage' configuration option? =

The OCR plugin is tailored to detect text in images with ~12pt text at 72dpi.
GD is used to upscale the temporary TIFF images fed to Tesseract as Tesseract is generally more accurate with
larger type, even if it's been upscaled from a smaller source. If you wish to disable this option simply set this
configuration option to `100%` and no resizing will occur.

= What if I just want to use the plugin but not install anything?

Hosting options are available. See [https://tattersoftware.com](https://tattersoftware.com)
for contact info.

== Changelog ==

= 0.1.0 =
Initial Release.

= 1.0.0 =
Complete rewrite: updates for PHP 7, ImageMagick replaced by GD, added language support
