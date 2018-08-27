<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://echo5digital.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Bookings_Persons_Range_Cost
 * @subpackage Woocommerce_Bookings_Persons_Range_Cost/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Woocommerce_Bookings_Persons_Range_Cost
 * @subpackage Woocommerce_Bookings_Persons_Range_Cost/includes
 * @author     Joshua Flowers <joshua@echo5digital.com>
 */
class Woocommerce_Bookings_Persons_Range_Cost_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'woocommerce-bookings-persons-range-cost',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
