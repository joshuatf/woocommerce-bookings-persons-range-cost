<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://echo5digital.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Bookings_Persons_Range_Cost
 * @subpackage Woocommerce_Bookings_Persons_Range_Cost/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woocommerce_Bookings_Persons_Range_Cost
 * @subpackage Woocommerce_Bookings_Persons_Range_Cost/admin
 * @author     Joshua Flowers <joshua@echo5digital.com>
 */
class Woocommerce_Bookings_Persons_Range_Cost_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce-bookings-persons-range-cost-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add persons field to range cost table
	 *
	 * @param array $pricing
	 * @param int $post_id
	 * @return void
	 */
	public function add_persons_field( $pricing, $post_id ) {
		$product = wc_get_product( $post_id );
		?>
			</td>
			<td>
				<select name="wc_booking_pricing_person[]">
					<option><?php _e( 'None', '' ); ?></option>
					<?php
						foreach ( $product->get_person_types() as $person_type ) {
							echo '<option value="' . $person_type->get_id() .'" ' . ( $pricing['person'] == $person_type->get_id() ? 'selected' : '' ) . '>' . $person_type->get_name() .'</option>';
						}
					?>
				</select>
			</td>
		<?php
	}

	public function save_person_meta( $post_id ) {
		$product = new WC_Product_Booking( $post_id );
		$product->set_props( array(
			'pricing' => $this->get_posted_pricing()
		) );
		$product->save();
	}

	/**
	 * Get posted pricing fields and format.
	 *
	 * @return array
	 */
	private function get_posted_pricing() {
		$pricing = array();
		$row_size     = isset( $_POST['wc_booking_pricing_type'] ) ? sizeof( $_POST['wc_booking_pricing_type'] ) : 0;
		for ( $i = 0; $i < $row_size; $i ++ ) {
			$pricing[ $i ]['type']          = wc_clean( $_POST['wc_booking_pricing_type'][ $i ] );
			$pricing[ $i ]['cost']          = wc_clean( $_POST['wc_booking_pricing_cost'][ $i ] );
			$pricing[ $i ]['modifier']      = wc_clean( $_POST['wc_booking_pricing_cost_modifier'][ $i ] );
			$pricing[ $i ]['base_cost']     = wc_clean( $_POST['wc_booking_pricing_base_cost'][ $i ] );
			$pricing[ $i ]['base_modifier'] = wc_clean( $_POST['wc_booking_pricing_base_cost_modifier'][ $i ] );
			$pricing[ $i ]['person'] = wc_clean( $_POST['wc_booking_pricing_person'][ $i ] );

			switch ( $pricing[ $i ]['type'] ) {
				case 'custom':
					$pricing[ $i ]['from'] = wc_clean( $_POST['wc_booking_pricing_from_date'][ $i ] );
					$pricing[ $i ]['to']   = wc_clean( $_POST['wc_booking_pricing_to_date'][ $i ] );
					break;
				case 'months':
					$pricing[ $i ]['from'] = wc_clean( $_POST['wc_booking_pricing_from_month'][ $i ] );
					$pricing[ $i ]['to']   = wc_clean( $_POST['wc_booking_pricing_to_month'][ $i ] );
					break;
				case 'weeks':
					$pricing[ $i ]['from'] = wc_clean( $_POST['wc_booking_pricing_from_week'][ $i ] );
					$pricing[ $i ]['to']   = wc_clean( $_POST['wc_booking_pricing_to_week'][ $i ] );
					break;
				case 'days':
					$pricing[ $i ]['from'] = wc_clean( $_POST['wc_booking_pricing_from_day_of_week'][ $i ] );
					$pricing[ $i ]['to']   = wc_clean( $_POST['wc_booking_pricing_to_day_of_week'][ $i ] );
					break;
				case 'time':
				case 'time:1':
				case 'time:2':
				case 'time:3':
				case 'time:4':
				case 'time:5':
				case 'time:6':
				case 'time:7':
					$pricing[ $i ]['from'] = wc_booking_sanitize_time( $_POST['wc_booking_pricing_from_time'][ $i ] );
					$pricing[ $i ]['to']   = wc_booking_sanitize_time( $_POST['wc_booking_pricing_to_time'][ $i ] );
					break;
				case 'time:range':
					$pricing[ $i ]['from'] = wc_booking_sanitize_time( $_POST['wc_booking_pricing_from_time'][ $i ] );
					$pricing[ $i ]['to']   = wc_booking_sanitize_time( $_POST['wc_booking_pricing_to_time'][ $i ] );

					$pricing[ $i ]['from_date'] = wc_clean( $_POST['wc_booking_pricing_from_date'][ $i ] );
					$pricing[ $i ]['to_date']   = wc_clean( $_POST['wc_booking_pricing_to_date'][ $i ] );
					break;
				default:
					$pricing[ $i ]['from'] = wc_clean( $_POST['wc_booking_pricing_from'][ $i ] );
					$pricing[ $i ]['to']   = wc_clean( $_POST['wc_booking_pricing_to'][ $i ] );
					break;
			}
		}
		return $pricing;
	}
}
