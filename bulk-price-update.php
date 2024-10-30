<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link             https://knovator.com/services/wordpress-plugin-development/
 * @since             1.0.0
 * @package           Bulk_Price_Update
 * Plugin Name:       Bulk Product Price Update for Woocommerce
 * Plugin URI:        https://knovator.com
 * Description:       you can update the price of products in bulk based on some parameters
 * Version:           1.0.0
 * Author:            Tushar satani
 * Author URI:        https://knovator.com/services/wordpress-plugin-development/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bulk-price-update
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BULK_PRICE_UPDATE_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bulk-price-update-activator.php
 */
function activate_bulk_price_update() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bulk-price-update-activator.php';
	Bulk_Price_Update_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bulk-price-update-deactivator.php
 */
function deactivate_bulk_price_update() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bulk-price-update-deactivator.php';
	Bulk_Price_Update_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_bulk_price_update' );
register_deactivation_hook( __FILE__, 'deactivate_bulk_price_update' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-bulk-price-update.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_bulk_price_update() {

	$plugin = new Bulk_Price_Update();
	$plugin->run();

}
run_bulk_price_update();
