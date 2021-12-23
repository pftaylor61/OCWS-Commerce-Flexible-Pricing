<?php
/*
Plugin Name: OCWS Commerce Name Your Price 
Plugin URI: https://github.com/pftaylor61
Description: The plugin allows the customer to give his own price and proceed with the checkout. You can easily set the minimum price for your woocommerce products both globally and individually.<br /><br />This plugin is a fork of 'ELEX Woocommerce Name Your Price' from ELEX Extensions (https://elextensions.com/). It has been forked to be adapted for Classic Commerce. All the hard work has been done by ELEX, with just a couple of lines changed by me, in order to make the plugin work with Classic Commerce.
Author: Paul Taylor, ELEX
Author URI: https://github.com/pftaylor61
Version: 1.2.0
Developer: Old Castle Web
Classic Commerce requires at least: 1.0.3
Classic Commerce tested up to: 1.0.3
*/

// echo "<b>Please Activate Woocommerce or Classic Commerce</b>";
// exit;
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Hey, Please Login!' );
}
define( 'PLUGIN_NAME', plugin_basename( dirname( __FILE__ ) ) );

require  'includes/scripts.php' ;  //registering and loading scripts
require  'includes/elex-wfp-product-flexible-price.php' ; //Contains all hooks and data filtering functions
require  'includes/elex-wfp-variable-product-flexible-pricing.php' ;
$commerce_dir = 'woocommerce';
if (is_plugin_active('classic-commerce/classic-commerce.php')) {
    $commerce_dir = 'classic-commerce';
}
require_once  WP_PLUGIN_DIR . '/'.$commerce_dir.'/includes/admin/settings/class-wc-settings-page.php' ;
require_once  ABSPATH . 'wp-admin/includes/plugin.php' ;

if ( (is_plugin_active( 'classic-commerce/classic-commerce.php' )) || (is_plugin_active( 'woocommerce/woocommerce.php' )) ) {
        
	if ( ! class_exists( 'Elex_Wfp_Name_Your_Price_Setting' ) ) {

		class Elex_Wfp_Name_Your_Price_Setting extends WC_Settings_Page {
			
			public function __construct() {
				$this->id    = 'elex-flexible-pricing';
				$this->label = __( 'Name Your Price', 'elex_wfp_flexible_price_domain' );
				add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_page' ), 99 );
				add_action( 'woocommerce_sections_' . $this->id, array( $this, 'output_sections' ) );
				add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
				add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
				add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'elex_wfp_action_links' ) );
			}

			public function get_sections() {
				$sections = array(
					''                    => __( 'General Settings', 'elex_wfp_flexible_price_domain' ),
				'<a href=https://github.com/pftaylor61> ' . __( 'My Plugins', 'elex_wfp_flexible_price_domain' ) . '</a>',
				);
				return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
			}

			public function output_sections() {
				global $current_section;
				$sections = $this->get_sections();
				if ( empty( $sections ) || 1 === count( $sections ) ) {
					return;
				}
				echo '<ul class="subsubsub">';
				$array_keys = array_keys( $sections );
				foreach ( $sections as $id => $label ) {
					echo '<li><a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=' . $this->id . '&section=' . sanitize_title( $id ) ) ) . '" class="' . ( $current_section === $id ? 'current' : '' ) . '">' . wp_kses_post( $label ) . '</a> ' . ( end( $array_keys ) === $id ? '' : '|' ) . ' </li>';
				}
				echo '</ul><br class="clear" />';
			}

			public function get_settings() {
				global $woocommerce , $current_section;
				$settings = array();
				if ( 'interested_products' === $current_section ) {
					
					$woocommerce_version = function_exists( 'WC' ) ? WC()->version : $woocommerce->version;
					wp_enqueue_style( 'elex-cpp-plugin-bootstrap', plugins_url( 'woocomerce-name-your-price-basic/assets/css/bootstrap.css', dirname( __FILE__ ) ), array(), $woocommerce_version );
					include_once  'includes/market.php' ;
				} else {
					$settings = array(
						'section_title'             => array(
							'name' => __( 'OCWS Commerce Flexible Pricing (Name Your Price)', 'elex_wfp_flexible_price_domain' ),
							'type' => 'title',
							'id'   => 'elex_cpp_settings_min_product_title',
						),
						'enable_checkbox'           => array(
							'name'     => __( 'Product Min Price', 'elex_wfp_flexible_price_domain' ),
							'type'     => 'checkbox',
							'desc'     => __( 'Enable', 'elex_wfp_flexible_price_domain' ),
							'label'    => __( 'Enable', 'elex_wfp_flexible_price_domain' ),
							'desc_tip' => __( 'Enable this option to set a minimum price for all the products. <br>Please note, if you have set a minimum price at individual product level, it will override this value.', 'elex_wfp_flexible_price_domain' ),
							'id'       => 'elex_cpp_settings_enable_min_product_price',
						),
						
						'product_min_price'         => array(
							'name'     => __( 'Set Min Price ', 'elex_wfp_flexible_price_domain' ) . '(' . get_woocommerce_currency_symbol() . ')',
							'type'     => 'number',
							'custom_attributes' => array(
								'step' => 'any',
								'min'  => '00',
							),
							'css'      => 'width:200px',
							'desc_tip' => true,
							'desc'     => __( 'Set the minimum price which the customer can proceed with payment.', 'elex_wfp_flexible_price_domain' ),
							'id'       => 'elex_cpp_settings_tab_min_product_price',
						),
						'product_dynamic_label'     => array(
							'name'        => __( 'Min Price Label', 'elex_wfp_flexible_price_domain' ),
							'type'        => 'text',
							'placeholder' => __( 'Enter Your Price', 'elex_wfp_flexible_price_domain' ),
							'css'         => 'width:400px',
							'desc_tip'    => true,
							'desc'        => __( 'Enter the label for the minimum price.', 'elex_wfp_flexible_price_domain' ),
							'id'          => 'elex_cpp_settings_tab_min_product_price_label',
						),
						'product_description_label' => array(
							'name'        => __( 'Min Price Description', 'elex_wfp_flexible_price_domain' ),
							'type'        => 'textarea',
							'css'         => 'width:400px',
							'placeholder' => __( 'A simple min price applied to product', 'elex_wfp_flexible_price_domain' ),
							'desc_tip'    => true,
							'desc'        => __( 'Enter Descripton for your minimum price.', 'elex_wfp_flexible_price_domain' ),
							'id'          => 'elex_cpp_settings_tab_min_product_price_description',
						),
						'enable_regular_sale_price_checkbox' => array(
							'name'     => __( 'Hide Price', 'elex_wfp_flexible_price_domain' ),
							'type'     => 'checkbox',
							'desc'     => __( 'Enable', 'elex_wfp_flexible_price_domain' ),
							'label'    => __( 'Enable', 'elex_wfp_flexible_price_domain' ),
							'desc_tip' => __( 'Enabling this option will hide the prices from product page.', 'elex_wfp_flexible_price_domain' ),
							'id'       => 'elex_cpp_settings_enable_regular_sale_price_on_product_page',
						),
						'section_end'               => array(
							'type' => 'sectionend',
							'id'   => 'wc_elex_flexible_pricing_section_end',
						),
					);
				}
				return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings );
			}

			public function output() {
				$settings = $this->get_settings();
				WC_Admin_Settings::output_fields( $settings );
			}

			public function save() {
				global $current_section;
				$settings = $this->get_settings();
				WC_Admin_Settings::save_fields( $settings );

				if ( $current_section ) {
					do_action( 'woocommerce_update_options_' . $this->id . '_' . $current_section );
				}
			}

			
                        public function elex_wfp_action_links( $links ) {
				$plugin_links = array(
					'<a href="' . admin_url( '/admin.php?page=wc-settings&tab=elex-flexible-pricing' ) . '">' . __( 'Settings', 'elex_wfp_flexible_price_domain' ) . '</a>',
					
				);
				return array_merge( $plugin_links, $links );
			} // end function elex_wfp_action_links
                        
                       
		}
	}
	new Elex_Wfp_Name_Your_Price_Setting();
} else {
	add_action( 'admin_notices', 'elex_wfp_woocommerce_inactive_notice' );
	return;
}

/** Function to notify if woocommerce is active */
function elex_wfp_woocommerce_inactive_notice() {
	?>
	<div id="message" class="error">
		<p>
		<?php	echo esc_html( __( 'WooCommerce plugin must be active for Address Validation & Google Address Auto Complete Plugin for WooCommerce(Basic) to work. ', 'wf-address-autocomplete-validation' ) ); ?>
		</p>
	</div>
	<?php
}

/** Load Plugin Text Domain. */

function elex_wfp_load_plugin_textdomain() {
	load_plugin_textdomain( 'elex_wfp_flexible_price_domain', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
}
add_action( 'plugins_loaded', 'elex_wfp_load_plugin_textdomain' );

function elex_wfp_wc_ajax_get_refreshed_fragments() {
	global $woocommerce;
	if ( $woocommerce->cart ) {
		$woocommerce->cart->calculate_totals();
	}
}
add_action( 'wc_ajax_get_refreshed_fragments', 'elex_wfp_wc_ajax_get_refreshed_fragments', 1 );





