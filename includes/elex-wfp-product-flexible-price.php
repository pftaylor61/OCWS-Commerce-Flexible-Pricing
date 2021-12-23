<?php


function elex_wfp_enable_custom_field() {
	global $post;
	$product = wc_get_product( $post->ID );
	if ( ! $product->is_type( 'variable' ) ) {
		?>
<hr style="color:black;">
<span class="elex_cpp_custom_title_heading"><?php echo esc_html_e( __( 'Elex Name Your Price', 'elex_wfp_flexible_price_domain' ) ); ?></span>


<?php

	woocommerce_wp_checkbox(
		array(
			'id'          => 'elex_wfp_custom_text_field_flag',
			'desc'        => __( 'Enable', 'elex_wfp_flexible_price_domain' ),
			'desc_tip'    => __( 'Enable', 'elex_wfp_flexible_price_domain' ),
			'label'       => __( 'Product Min Price', 'elex_wfp_flexible_price_domain' ),
			'description' => __( 'Enable this option to set a minimum price for this product. Please note, this value will override the global minimum price that you may have set.', 'elex_wfp_flexible_price_domain' ),
			'value'       => get_post_meta( $post->ID, 'elex_wfp_custom_text_field_flag', true ),
		)
	);
	woocommerce_wp_text_input(
		array(
			'id'                => 'elex_wfp_custom_price_text_field',
			'label'             => __( 'Set Min Price ', 'elex_wfp_flexible_price_domain' ) . '(' . get_woocommerce_currency_symbol() . ')',
			'class'             => 'elex-wfp-custom-field',
		
			'type'              => 'number',
			'description'       => __( 'Set the minimum price which the customer can proceed with payment.', 'elex_wfp_flexible_price_domain' ),
			'custom_attributes' => array(
				'step' => 'any',
				'min'  => '00',
			),
			'desc_tip'          => true,
			'value'             => get_post_meta( $post->ID, 'elex_wfp_custom_price_text_field', true ),
		)
	);
	woocommerce_wp_text_input( 
		array(
			'id'                => 'elex_wfp_product_min_price_dynamic_label',
			'label'             => __( 'Min Price Label', 'elex_wfp_flexible_price_domain' ),
			'class'             => 'elex-wfp-custom-field',
		
			'type'              => 'text',
			'description'       => __( 'Enter the label for the minimum price.', 'elex_wfp_flexible_price_domain' ),
			'custom_attributes' => array(
				'step' => '1',
				'min'  => '00',
			),
			'desc_tip'          => true,
			'value'             => get_post_meta( $post->ID, 'elex_wfp_product_min_price_dynamic_label', true ),
		)
	);
	woocommerce_wp_text_input(
		array(
			'id'                => 'elex_wfp_product_min_price_description',
			'label'             => __( 'Min Price Description', 'elex_wfp_flexible_price_domain' ),
			'class'             => 'elex-wfp-custom-field',
		
			'type'              => 'text',
			'description'       => __( 'Enter Descripton for your minimum price.', 'elex_wfp_flexible_price_domain' ),
			'custom_attributes' => array(
				'step' => '1',
				'min'  => '00',
			),
			'desc_tip'          => true,
			'value'             => get_post_meta( $post->ID, 'elex_wfp_product_min_price_description', true ),
		)
	);
	woocommerce_wp_checkbox(
		array(
			'id'          => 'elex_wfp_hide_price_regular_sale_flag',
			'desc'        => __( 'Enable', 'elex_wfp_flexible_price_domain' ),
			'desc_tip'    => __( 'Enable', 'elex_wfp_flexible_price_domain' ),
			'label'       => __( 'Hide Price', 'elex_wfp_flexible_price_domain' ),
			'description' => __( 'Display Regualar/Sale price', 'elex_wfp_flexible_price_domain' ),
			'value'       => get_post_meta( $post->ID, 'elex_wfp_hide_price_regular_sale_flag', true ),
		)
	);
	}
}
add_action( 'woocommerce_product_options_general_product_data', 'elex_wfp_enable_custom_field' );

function elex_wfp_save_custom_field( $post_id ) {
	if ( ! ( isset( $_POST['woocommerce_meta_nonce'] ) || wp_verify_nonce( sanitize_key( $_POST['woocommerce_meta_nonce'] ), 'woocommerce_save_data' ) ) ) { // Input var okay.
		return false;
	}
	$product = wc_get_product( $post_id );
	if ( $product->is_type( 'simple' ) ) {
		$flag = isset( $_POST['elex_wfp_custom_text_field_flag'] ) ? sanitize_text_field( $_POST['elex_wfp_custom_text_field_flag'] ) : '';
		update_post_meta( $post_id, 'elex_wfp_custom_text_field_flag', $flag );
		
		$custom_price = isset( $_POST['elex_wfp_custom_price_text_field'] ) ? sanitize_text_field( $_POST['elex_wfp_custom_price_text_field'] ) : '';
		update_post_meta( $post_id, 'elex_wfp_custom_price_text_field', $custom_price );

		$custom_price_label = isset( $_POST['elex_wfp_product_min_price_dynamic_label'] ) ? sanitize_text_field( $_POST['elex_wfp_product_min_price_dynamic_label'] ) : '';
		update_post_meta( $post_id, 'elex_wfp_product_min_price_dynamic_label', $custom_price_label );

		$custom_price_desc = isset( $_POST['elex_wfp_product_min_price_description'] ) ? sanitize_text_field( $_POST['elex_wfp_product_min_price_description'] ) : '';
		update_post_meta( $post_id, 'elex_wfp_product_min_price_description', $custom_price_desc );
		
		$hide_price = isset( $_POST['elex_wfp_hide_price_regular_sale_flag'] ) ? sanitize_text_field( $_POST['elex_wfp_hide_price_regular_sale_flag'] ) : '';
		update_post_meta( $post_id, 'elex_wfp_hide_price_regular_sale_flag', $hide_price );
	
		$product->save();
	}
	
}

add_action( 'woocommerce_process_product_meta', 'elex_wfp_save_custom_field' );


function elex_wfp_hide_product_price( $price ) {
	return '';
}

function elex_wfp_hide_price_script() {
	?>
	<script>
	jQuery(document).ready(function() {
		jQuery('p.price').css("display", "none")
	});
	</script>
	<?php
}


function elex_wfp_display_custom_field() {

	global $post;
	$product = wc_get_product( $post->ID );
	wp_nonce_field( basename( __FILE__ ), 'elex-wfp-custom-price-field-nonce' );
	if ( $product->is_type( 'simple' ) ) {
	
		if ( 'yes' !== $product->get_meta( 'elex_wfp_custom_text_field_flag' ) && 'no' === get_option( 'elex_cpp_settings_enable_min_product_price' ) ) {
			return;
		}
		
		if ( 'yes' === $product->get_meta( 'elex_wfp_custom_text_field_flag' ) ) {
			if ( 'yes' === $product->get_meta( 'elex_wfp_hide_price_regular_sale_flag' ) ) {
			   elex_wfp_hide_price_script();
			}
		} elseif ( 'yes' === get_option( 'elex_cpp_settings_enable_min_product_price' ) ) {
			if ( 'yes' === get_option( 'elex_cpp_settings_enable_regular_sale_price_on_product_page' ) ) {
				elex_wfp_hide_price_script();
			}
		}
		$product_min_price = $product->get_meta( 'elex_wfp_custom_price_text_field' );
		$flag              = $product->get_meta( 'elex_wfp_custom_text_field_flag' );
		$product_min_label = $product->get_meta( 'elex_wfp_product_min_price_dynamic_label' );
		$product_min_label = elex_wfp_return_wpml_string( $product_min_label, 'Product price label' );  
		$product_min_desc  = $product->get_meta( 'elex_wfp_product_min_price_description' );
		$product_min_desc  = elex_wfp_return_wpml_string( $product_min_desc, 'Product price description' );  
		
		if ( $product->get_meta( 'elex_wfp_custom_text_field_flag' ) !== 'yes' ) {
			$product_min_price = get_option( 'elex_cpp_settings_tab_min_product_price', 1 );
			$product_min_desc  = get_option( 'elex_cpp_settings_tab_min_product_price_description', 1 );
			$product_min_desc  = elex_wfp_return_wpml_string( $product_min_desc, 'Product price description' ); 
			$product_min_label = get_option( 'elex_cpp_settings_tab_min_product_price_label', 1 );
			$product_min_label = elex_wfp_return_wpml_string( $product_min_label, 'Product price label' );  
			
		}

		if ( isset( $produc_min_label ) ) {
			$product_min_label = __( 'Enter Your Price', 'elex_wfp_flexible_price_domain' );
		}
		
		
		echo '<div class="wrap">';
		?>

			<label class="custom-min-price1" for="custom-price-field"><?php echo wp_kses_post( $product_min_label ) . ' (' . wp_kses_post( get_woocommerce_currency_symbol() ) . ')'; ?></label>
			<input type="number" step="any" class="custom-price" value="<?php echo  wp_kses_post( $product_min_price ); ?>" id="custom_price_field" name="custom_price_field"  />
			<small class="description_product"> <?php echo '*' . wp_kses_post( $product_min_desc ); ?> </small>
			<?php
		echo '</div>';
	} 
}


add_action( 'woocommerce_before_add_to_cart_button', 'elex_wfp_display_custom_field' );
function elex_wfp_ajax_load_scripts() {
	global $woocommerce;
	$woocommerce_version = function_exists( 'WC' ) ? WC()->version : $woocommerce->version;
	wp_nonce_field( basename( __FILE__ ), 'elex-wfp-custom-price-field-nonce' );
	wp_enqueue_script( 'ajax-test', plugins_url( '/assets/js/ajax-test.js', dirname( __FILE__ ) ), array( 'jquery' ), $woocommerce_version );
	wp_localize_script( 
		'ajax-test', 
		'the_ajax_script', 
		array( 
			'ajaxurl'                        => admin_url( 'admin-ajax.php' ), 
			'elex_wfp_variation_nonce_token' => wp_create_nonce( 'elex_wfp_variation_nonce' ),
		) 
	);  
}
add_action( 'wp_print_scripts', 'elex_wfp_ajax_load_scripts' );

function elex_wfp_ajax_process_request() {
	
	if ( ! ( isset( $_POST['_ajax_nonce'] ) || wp_verify_nonce( sanitize_key( $_POST['_ajax_nonce'] ), 'woocommerce_save_data' ) ) ) { // Input var okay.
		return false;
	}
	$var_id          = isset( $_POST['var_id'] ) ? sanitize_text_field( $_POST['var_id'] ) : '';
	$enable_checkbox = get_post_meta( sanitize_text_field( $_POST['var_id'] ), 'elex_wfp_checkbox', true );
	
	if ( 'no' === get_option( 'elex_cpp_settings_enable_min_product_price' ) && 'yes' !== $enable_checkbox ) {
		$json_response = array(
			'general_flag'    => 'no',
			'value'           => '',
			'label'           => '',
			'desc'            => '', 
			'hide_price'      => get_option( 'elex_cpp_settings_enable_regular_sale_price_on_product_page' ),
			'currency_symbol' => get_woocommerce_currency_symbol(),
		);
		echo json_encode( $json_response );
		die();
	}
	if ( 'yes' === $enable_checkbox ) {
	
		$custom_field_value = get_post_meta( $var_id, 'elex_wfp_text_field', true );
		$custom_field_label = get_post_meta( $var_id, 'elex_wfp_label_field', true ); 
		$custom_field_label = elex_wfp_return_wpml_string( $custom_field_label, 'Product variation price label' ); 
		$custom_field_desc  = get_post_meta( $var_id , 'elex_wfp_desc_field', true );
		$custom_field_desc  = elex_wfp_return_wpml_string( $custom_field_desc, 'Product variation price description' );   
		$enable_price_flag  = get_post_meta( $var_id , 'elex_wfp_enable_price', true );
	} else {
		$custom_field_value = get_option( 'elex_cpp_settings_tab_min_product_price' );
		$custom_field_label = get_option( 'elex_cpp_settings_tab_min_product_price_label' ); 
		$custom_field_label = elex_wfp_return_wpml_string( $custom_field_label, 'Product variation price label text' );
		$custom_field_desc  = get_option( 'elex_cpp_settings_tab_min_product_price_description' );  
		$custom_field_desc  = elex_wfp_return_wpml_string( $custom_field_desc, 'Product variation price description' );
		$enable_price_flag  = get_option( 'elex_cpp_settings_enable_regular_sale_price_on_product_page' );
	
	}
	$json_response = array(
		'general_flag'    => 'yes',
		'value'           => $custom_field_value,
		'label'           => $custom_field_label,
		'desc'            => $custom_field_desc, 
		'hide_price'      => $enable_price_flag,
		'currency_symbol' => get_woocommerce_currency_symbol(),
	);
	echo json_encode( $json_response );
	die();

}
add_action( 'wp_ajax_test_response', 'elex_wfp_ajax_process_request' );
add_action( 'wp_ajax_nopriv_test_response', 'elex_wfp_ajax_process_request' );

function elex_wfp_cart_validation( $passed, $product_id, $quantity, $variation_id = null ) {

	if ( ! ( isset( $_POST['elex-wfp-custom-price-field-nonce'] ) || wp_verify_nonce( sanitize_key( $_POST['elex-wfp-custom-price-field-nonce'] ), 'woocommerce_save_data' ) ) ) {
		return false;
	}
	$product = wc_get_product( $product_id );
	if ( $product->is_type( 'simple' ) ) {
		$product_min_price = $product->get_meta( 'elex_wfp_custom_price_text_field' );
		$flag              = $product->get_meta( 'elex_wfp_custom_text_field_flag' );
		if ( 'no' === get_option( 'elex_cpp_settings_enable_min_product_price' ) && 'yes' !== $product->get_meta( 'elex_wfp_custom_text_field_flag' ) ) {
			$passed;
		} else {
			if ( empty( sanitize_text_field( $_POST['custom_price_field'] ) ) && sanitize_text_field( $_POST['custom_price_field'] ) !== 0 ) {
				$passed = false;
				wc_add_notice( __( 'Custom Price is a required field.', 'elex_wfp_flexible_price_domain' ), 'error' );
				return $passed;
			}
			
			if ( 'yes' === $flag ) {
				if ( $product_min_price > sanitize_text_field( (float) $_POST['custom_price_field'] ) ) {
					$passed = false;
					wc_add_notice( __( 'Custom Price Cannot be less than ', 'elex_wfp_flexible_price_domain' ) . $product_min_price, 'error' );
					return $passed;
				}           
			} else {
				if ( get_option( 'elex_cpp_settings_tab_min_product_price', 1 ) > sanitize_text_field( (float) $_POST['custom_price_field'] ) ) {
					$passed = false;
					wc_add_notice( __( 'Custom Price Cannot be less than ', 'elex_wfp_flexible_price_domain' ) . get_option( 'elex_cpp_settings_tab_min_product_price', 1 ), 'error' );
					return $passed;
				}           
			}
			update_post_meta( $product_id, 'custom_price_field', sanitize_text_field( $_POST['custom_price_field'] ) );
		} 
		return $passed;
	} elseif ( $product->is_type( 'variable' ) ) {
		if ( 'no' === get_option( 'elex_cpp_settings_enable_min_product_price' ) && 'yes' !== get_post_meta( $variation_id, 'elex_wfp_checkbox', true ) ) {
			$passed;
		} else {
			if ( empty( sanitize_text_field( $_POST['custom_price_field_variation'] ) ) && sanitize_text_field( $_POST['custom_price_field_variation'] ) !== 0 ) {
				$passed = false;
				wc_add_notice( __( 'Custom Price is a required field.', 'elex_wfp_flexible_price_domain' ), 'error' );
				return $passed;
			}
			if ( get_post_meta( $variation_id, 'elex_wfp_checkbox', true ) === 'yes' ) {
				if ( get_post_meta( $variation_id, 'elex_wfp_text_field', true ) > sanitize_text_field( (float) $_POST['custom_price_field_variation'] ) ) {
					$passed = false;
					wc_add_notice( __( 'Custom Price Cannot be less than ' , 'elex_wfp_flexible_price_domain' ) . get_post_meta( $variation_id, 'elex_wfp_text_field', true ), 'error' );
				}
			} else {
				if ( get_option( 'elex_cpp_settings_tab_min_product_price', 1 ) > sanitize_text_field( (float) $_POST['custom_price_field_variation'] ) ) {
					$passed = false;
					wc_add_notice( __( 'Custom Price Cannot be less than ', 'elex_wfp_flexible_price_domain' ) . get_option( 'elex_cpp_settings_tab_min_product_price', 1 ), 'error' );
					return $passed;
				}
			}
			update_post_meta( $product_id, 'custom_price_field', sanitize_text_field( $_POST['custom_price_field_variation'] ) );
		}
		
		return $passed;
	}
}

add_filter( 'woocommerce_add_to_cart_validation', 'elex_wfp_cart_validation', 10, 4 );

function elex_wfp_update_cart_item_data( $cart_item_data, $product_id, $variation_id ) {
	
	$product = wc_get_product( $product_id ); 
	if ( $product->is_type( 'simple' ) ) { 
		$flag = $product->get_meta( 'elex_wfp_custom_text_field_flag' );
	} elseif ( $product->is_type( 'variable' ) ) {
		$flag = get_post_meta( $variation_id, 'elex_wfp_checkbox', true );
	}

	if ( 'yes' !== $flag && get_option( 'elex_cpp_settings_enable_min_product_price' ) === 'no' ) {
		return $cart_item_data;       
	}
	$cart_item_data['custom_price_field'] = get_post_meta( $product_id, 'custom_price_field', true );
	$price                                = $product->get_price();
	$cart_item_data['total_price']        = get_post_meta( $product_id, 'custom_price_field', true );
	return $cart_item_data;
}
add_filter( 'woocommerce_add_cart_item_data', 'elex_wfp_update_cart_item_data', 10, 3 );

function elex_wfp_before_calculate_totals( $cart_obj ) {

	foreach ( $cart_obj->get_cart() as $key => $value ) {
		if ( isset( $value['total_price'] ) ) {
			$price = $value['total_price'];
			$value['data']->set_price( $price );
		}
	}
}
add_action( 'woocommerce_before_calculate_totals', 'elex_wfp_before_calculate_totals', 10, 3 );

function elex_wfp_return_wpml_string( $string_to_translate, $name ) {
	// https://wpml.org/documentation/support/wpml-coding-api/wpml-hooks-reference/#hook-620585
	// https://wpml.org/documentation/support/wpml-coding-api/wpml-hooks-reference/#hook-620618
	$package = array(
		'kind'      => 'Elex Woocommerce Flexible Pricing',
		'name'      => 'elex_wfp_flexible_price_domain',
		'title'     => $name,
		'edit_link' => '',
	);
	do_action( 'wpml_register_string', $string_to_translate, $name, $package, $name, 'LINE' );
	$ret_string = apply_filters( 'wpml_translate_string', $string_to_translate, $name, $package );
	return $ret_string;
}
