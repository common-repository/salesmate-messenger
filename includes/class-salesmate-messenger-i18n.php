<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.salesmate.io
 * @since      1.0
 *
 * @package    Salesmate_Messenger
 * @subpackage Salesmate_Messenger/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0
 * @package    Salesmate_Messenger
 * @subpackage Salesmate_Messenger/includes
 * @author     Salesmate <info@salesmate.io>
 */
class Salesmate_Messenger_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'salesmate-messenger',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
