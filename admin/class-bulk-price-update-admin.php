<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://knovator.com/wordpress-plugin-development-services/
 * @since      1.0.0
 *
 * @package    Bulk_Price_Update
 * @subpackage Bulk_Price_Update/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bulk_Price_Update
 * @subpackage Bulk_Price_Update/admin
 * @author     Tushar satani <tushar@knovator.com>
 */
class Bulk_Price_Update_Admin {

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
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bulk_Price_Update_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bulk_Price_Update_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/bulk-price-update-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'select2', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bulk_Price_Update_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bulk_Price_Update_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bulk-price-update-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script(
			$this->plugin_name,
			'plugin_data',
			array(
				'ajax_url'          => admin_url( 'admin-ajax.php' ),
				'edit_bulk_product' => wp_create_nonce( 'edit_bulk_product' ),
			)
		);
		wp_enqueue_script( 'select2', plugin_dir_url( __FILE__ ) . 'js/select2.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'sweetalert2', plugin_dir_url( __FILE__ ) . 'js/sweetalert2.min.js', array( 'jquery' ), $this->version, false );
	}
	/**
	 * Register the Menu for bulk price update for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function add_settings_menu() {
		add_menu_page(
			__( 'Bulk Product Price ', 'bulk-price-update' ),
			__( 'Bulk Product Price', 'bulk-price-update' ),
			'manage_options',
			'bulk-product-price',
			array( $this, 'settings_callback' ),
			'dashicons-update-alt',
			3
		);

	}
	/**
	 * Callbacl for menu function
	 *
	 * @since    1.0.0
	 */
	public function settings_callback() {

		include 'partials/bulk-price-update-admin-display.php';

	}
	/**
	 * Add multiselect to dropdown in category field
	 *
	 * @since    1.0.0
	 */
	public function dropdown_filter( $output, $r ) {
		if ( isset( $r['multiple'] ) && $r['multiple'] ) {
			$output   = preg_replace( '/^<select/i', '<select multiple data-live-search="true" data-style="btn-info"', $output );
			$output   = str_replace( "name='{$r['name']}'", "name='{$r['name']}[]'", $output );
			$selected = is_array( $r['selected'] ) ? $r['selected'] : explode( ',', $r['selected'] );
			foreach ( array_map( 'trim', $selected ) as $value ) {
				$output = str_replace( "value=\"{$value}\"", "value=\"{$value}\" selected", $output );
			}
		}
		return $output;
	}

	/**
	 * Ajax functionality for updating the product prices
	 *
	 * @since    1.0.0
	 */
	public function my_action() {
		check_ajax_referer( 'edit_bulk_product', 'edit_bulk_product' );
		$cat_slug          = isset( $_POST['categories'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['categories'] ) ) : $cat_slug = '';
		$percentage        = isset( $_POST['percentage'] ) ? wp_unslash( intval( $_POST['percentage'] ) ) : '';
		$price_change_type = isset( $_POST['price_action'] ) ? sanitize_text_field( wp_unslash( $_POST['price_action'] ) ) : '';
		$round_point       = isset( $_POST['round-off'] ) ? sanitize_text_field( wp_unslash( $_POST['round-off'] ) ) : '';
		$currency          = get_woocommerce_currency_symbol();
		$args              = array(
			'orderby'  => 'ID',
			'order'    => 'ASC',
			'limit'    => -1,
			'category' => $cat_slug,
		);
		$products          = wc_get_products( $args );
		$table             = '';
		$number            = 0;
		foreach ( $products as $product ) {
				$number++;
			if ( ! $product->is_type( 'variable' ) ) :
				$prod_reg_price  = get_post_meta( $product->get_id(), '_regular_price', true );
				$prod_sale_price = get_post_meta( $product->get_id(), '_sale_price', true );
				$prod_price      = get_post_meta( $product->get_id(), '_price', true );
				( $price_change_type == 'increase-percentge' ) ? $new_price = (float) ( $prod_reg_price + ( ( $percentage / 100 ) * $prod_reg_price ) ) : $new_price = (float) ( $prod_reg_price - ( ( $percentage / 100 ) * $prod_reg_price ) );
				( $round_point == 1 ) ? $new_price                          = round( $new_price ) : $new_price = $new_price;
				( $round_point == 1 ) ? $prod_price                         = round( $prod_price ) : $prod_price = $prod_price;
				if ( isset( $prod_sale_price ) && ( ! empty( $prod_sale_price ) ) ) :
					( $price_change_type == 'increase-percentge' ) ? $new_price     = (float) ( $prod_sale_price + ( ( $percentage / 100 ) * $prod_sale_price ) ) : $new_price = (float) ( $prod_sale_price - ( ( $percentage / 100 ) * $prod_sale_price ) );
					( $round_point == 1 ) ? $new_price                              = round( $new_price ) : $new_price = $new_price;
					( $round_point == 1 ) ? $prod_price                             = round( $prod_price ) : $prod_price = $prod_price;
					( $price_change_type == 'increase-percentge' ) ? $new_reg_price = (float) ( $prod_reg_price + ( ( $percentage / 100 ) * $prod_reg_price ) ) : $new_reg_price = (float) ( $prod_reg_price - ( ( $percentage / 100 ) * $prod_reg_price ) );
					( $round_point == 1 ) ? $new_reg_price                          = round( $new_reg_price ) : $new_reg_price = $new_reg_price;
				endif;
			endif;
				$table .= '<tr>
				<td>' . $number . '</td>
				<td>' . $product->get_image( 'thumbnail' ) . '</td>
				<td>' . $product->id . '</td>
				<td>' . $product->name . '</td>
				<td>' . $product->get_type() . '</td>
				<td>';
			if ( ! $product->is_type( 'variable' ) ) :
				$table .= '<strong>Old Price:</strong> <code>' . $currency . $prod_price . '</code><br> <strong>New Price:</strong> <code>' . $currency . $new_price . '</code>';
			else :
				$table .= '<strong>Buy Premium!</strong>';
			endif;
			$table .= '</td>
				</tr>';
			if ( ! $product->is_type( 'variable' ) ) :
				update_post_meta( $product->get_id(), '_price', $new_price );
				if ( isset( $prod_sale_price ) && ( ! empty( $prod_sale_price ) ) ) :
					update_post_meta( $product->get_id(), '_sale_price', $new_price );
					update_post_meta( $product->get_id(), '_regular_price', $new_reg_price );
					else :
						update_post_meta( $product->get_id(), '_regular_price', $new_price );
				endif;
			endif;
		}
		wp_send_json_success( $table );
		wp_die();
	}

}
