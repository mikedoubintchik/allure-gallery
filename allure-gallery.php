<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://allurewebsolutions.com
 * @since             1.0.0
 * @package           Allure_Gallery
 *
 * @wordpress-plugin
 * Plugin Name:       Allure Gallery
 * Plugin URI:        https://allure-gallery.allureprojects.com
 * Description:       This plugin allows you to change the native wordpress gallery into one with a large top image and thumbnails underneath.
 * Version:           1.2.2
 * Author:            Allure Web Solutions
 * Author URI:        https://allurewebsolutions.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       allure-gallery
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-allure-gallery-activator.php
 */
function activate_allure_gallery()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-allure-gallery-activator.php';
	Allure_Gallery_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-allure-gallery-deactivator.php
 */
function deactivate_allure_gallery()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-allure-gallery-deactivator.php';
	Allure_Gallery_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_allure_gallery');
register_deactivation_hook(__FILE__, 'deactivate_allure_gallery');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-allure-gallery.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_allure_gallery()
{

	$plugin = new Allure_Gallery();
	$plugin->run();
}
run_allure_gallery();
