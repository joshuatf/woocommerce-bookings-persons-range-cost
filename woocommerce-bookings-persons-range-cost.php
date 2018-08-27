<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://echo5digital.com
 * @since             1.0.0
 * @package           Woocommerce_Bookings_Persons_Range_Cost
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Bookings Persons Range Cost
 * Plugin URI:        #
 * Description:       This plugin adds a conditional persons field to the booking range costs.
 * Version:           1.0.0
 * Author:            Joshua Flowers
 * Author URI:        https://echo5digital.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocommerce-bookings-persons-range-cost
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
define( 'WC_BOOKINGS_PERSONS_RANGE_COST', '1.0.0' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-bookings-persons-range-cost.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woocommerce_bookings_persons_range_cost() {

	$plugin = new Woocommerce_Bookings_Persons_Range_Cost();
	$plugin->run();

}
run_woocommerce_bookings_persons_range_cost();
