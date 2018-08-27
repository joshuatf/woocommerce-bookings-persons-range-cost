<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://echo5digital.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Bookings_Persons_Range_Cost
 * @subpackage Woocommerce_Bookings_Persons_Range_Cost/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Woocommerce_Bookings_Persons_Range_Cost
 * @subpackage Woocommerce_Bookings_Persons_Range_Cost/public
 * @author     Joshua Flowers <joshua@echo5digital.com>
 */
class Woocommerce_Bookings_Persons_Range_Cost_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Bookings_Persons_Range_Cost_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Bookings_Persons_Range_Cost_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woocommerce-bookings-persons-range-cost-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Bookings_Persons_Range_Cost_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Bookings_Persons_Range_Cost_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce-bookings-persons-range-cost-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Modify the booking cost if within price change rule
	 *
	 * @param int $booking_cost
	 * @param WC_Product_Booking $booking_form
	 * @param array $posted
	 * @return int
	 */
	public function modify_booking_cost( $booking_cost, $booking_form, $posted ) {
		$data = $booking_form->get_posted_data( $posted );
		$additional_cost = 0;

		if ( isset( $data['_resource_id'] ) ) {
			$resource = $booking_form->product->get_resource( $data['_resource_id'] );
			$availability_rules = $resource->get_availability();
			usort( $availability_rules, function( $a, $b ) {
				return $b['priority'] < $a['priority'] ? -1 : 1;
			} );
			$processed_rules = $this->process_rules( $availability_rules );
			$timestamp = $data['_start_date'];

			while ( $timestamp < $data['_end_date'] ) {
				foreach ( $processed_rules as $key => $rule ) {
					if ( $this->check_if_timestamp_in_rule( $timestamp, $rule, false ) ) {
						$additional_cost = isset( $availability_rules[ $key ][ 'price_change' ] ) ? $availability_rules[ $key ][ 'price_change' ] * $data[ '_qty' ] : 0;
					}
				}
				$timestamp = strtotime( '+1 day', $timestamp );
			}
		}
		return $booking_cost + $additional_cost;
	}

	public function modify_cost_rules( $base_cost, $fields, $key ) {
		parse_str( $_POST['form'], $data );
		if ( intval( $fields['person'] ) > 0 ) {
			if ( isset( $data[ 'wc_bookings_field_persons_' . $fields['person'] ] )
				&& absint( $data[ 'wc_bookings_field_persons_' . $fields['person'] ] ) > 0 ) {
				return $base_cost * absint( $data[ 'wc_bookings_field_persons_' . $fields['person'] ] );
			}
			else {
				return 0;
			}
		}
		return $base_cost;
	}

}
