# WP Tesseract  

WordPress Plugin for Tesseract.

## Description 

A plugin for extracting text from attached images using
[OCR](http://en.wikipedia.org/wiki/Optical_character_recognition) via [Tesseract](https://github.com/tesseract-ocr/).
This plugin adds a new post named for each image upload containing any recognized text characters within the file.
This text can then be edited for accuracy and used elsewhere on the site.

The OCR plugin requires a supported version of PHP with the GD extension and the following command line utility:
* [Tesseract](https://github.com/tesseract-ocr/) for the actual OCR
This utility must be manually installed on your server and executable by PHP.
**This process, and consequently this plugin, is recommended only for advanced users.**


## Installation

1. Install Tesseract OCR on your server ([Tesseract wiki](https://github.com/tesseract-ocr/tesseract/wiki/))
2. Search and add the plugin from WordPress, or upload a copy of the source to your `/wp-content/plugins/` directory
3. Activate the plugin through the `Plugins` menu in WordPress
4. Configure the plugin through the `Settings > Tesseract` link in the sidebar menu in WordPress


## Frequently Asked Questions

### What is Tesseract OCR and where do I get it?

Tesseract OCR is an open source [optical character recognition](http://en.wikipedia.org/wiki/Optical_character_recognition)
library that the WordPress OCR plugin uses to extract text from images. The library as
well as installation instructions can be found at
[https://github.com/tesseract-ocr/tesseract/wiki/](https://github.com/tesseract-ocr/tesseract/wiki/).

### How do I know if / where I have Tesseract installed on my server?

Linux:

1. SSH into your server and type `which tesseract`.
2. If Tesseract is installed and in your shell environment PATH the terminal should return a path similar to `/opt/local/bin/tesseract`.
3. Place this path in the configuration of the OCR plugin through the `Settings > Tesseract` link in the sidebar menu in WordPress

### Where is the detected text stored? 

The text detected by the OCR plugin is added as a new post, named after the image file.

### What is the 'Resize percentage' configuration option?

The OCR plugin is tailored to detect text in images with ~12pt text at 72dpi. GD
is used to upscale the temporary images fed to Tesseract as Tesseract is generally
more accurate with larger type, even if it's been upscaled from a smaller source. If you
wish to disable this option simply set this configuration option to `100%` and no resizing
will occur.

### What if I just want to use the plugin but not install anything?

Hosting options are available. See [https://tattersoftware.com](https://tattersoftware.com)
for contact info.

## Attribution

The plugin's banner photo is by Ekrulila from Pexels.
