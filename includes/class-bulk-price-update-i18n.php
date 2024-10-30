<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://knovator.com/wordpress-plugin-development-services/
 * @since      1.0.0
 *
 * @package    Bulk_Price_Update
 * @subpackage Bulk_Price_Update/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Bulk_Price_Update
 * @subpackage Bulk_Price_Update/includes
 * @author     Tushar satani <tushar@knovator.com>
 */
class Bulk_Price_Update_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'bulk-price-update',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
