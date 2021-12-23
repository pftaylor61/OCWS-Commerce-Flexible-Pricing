<?php 

add_action( 'woocommerce_product_after_variable_attributes', 'elex_wfp_variation_settings_fields', 10, 3 );

function elex_wfp_variation_settings_fields( $loop, $variation_data, $variation ) {
	wp_nonce_field( basename( __FILE__ ), 'elex-wfp-variable-field-nonce' );
	?>
	<div class="wrap">
	<span class="elex_cpp_custom_title_heading"><?php echo esc_html_e( __( 'Elex Name Your Price', 'elex_wfp_flexible_price_domain' ) ); ?></span>

	<div class="wrap">
	<?php
	woocommerce_wp_checkbox( 
		array( 
			'id'          => 'elex_wfp_checkbox[' . $variation->ID . ']',
			'desc'        => __( 'Enable', 'elex_wfp_flexible_price_domain' ),
			'desc_tip'    => __( 'Enable', 'elex_wfp_flexible_price_domain' ),
			'label'       => __( 'Enable', 'elex_wfp_flexible_price_domain' ), 
			'description' => __( 'Enable this option to set a minimum price for this variation. Please note, this value will override the global minimum price that you may have set', 'elex_wfp_flexible_price_domain' ),
			'value'       => get_post_meta( $variation->ID, 'elex_wfp_checkbox', true ),
		)
	);
	woocommerce_wp_text_input( 
		array( 
			'id'                => 'elex_wfp_text_field[' . $variation->ID . ']', 
			'label'             => __( 'Set Min Price ', 'elex_wfp_flexible_price_domain' ) . '(' . get_woocommerce_currency_symbol() . ')',
			'type'              => 'number',
			'placeholder'       => __( 'Enter Your Price', 'elex_wfp_flexible_price_domain' ),
			'custom_attributes' => array(
				'step' => 'any',
				'min'  => '00',
			),
			'desc_tip'          => true,
			'description'       => __( 'Set the minimum price of your product.', 'elex_wfp_flexible_price_domain' ),
			'value'             => get_post_meta( $variation->ID, 'elex_wfp_text_field', true ),
		)
	);
	woocommerce_wp_text_input(
		array(
			'id'                => 'elex_wfp_label_field[' . $variation->ID . ']',
			'label'             => __( 'Min Price Label', 'elex_wfp_flexible_price_domain' ),
			'type'              => 'text',
			'default'           => __( 'Product Min Price', 'elex_wfp_flexible_price_domain' ),
			'css'               => 'width:400px',
			'custom_attributes' => array(
				'step' => '1',
				'min'  => '00',
			),
			'desc_tip'          => true,
			'description'       => __( 'Enter the label for the minimum price.', 'elex_wfp_flexible_price_domain' ),
		
			'value'             => get_post_meta( $variation->ID, 'elex_wfp_label_field', true ),
		)
	);
	woocommerce_wp_text_input( 
		array(
			'id'                => 'elex_wfp_desc_field[' . $variation->ID . ']',
			'label'             => __( 'Min Price Description', 'elex_wfp_flexible_price_domain' ),
			'class'             => 'elex-wfp-custom-field',
		
			'type'              => 'text',
			'description'       => __( 'Enter Descripton for your minimum price.', 'elex_wfp_flexible_price_domain' ),
			'custom_attributes' => array(
				'step' => '1',
				'min'  => '00',
			),
			'desc_tip'          => true,
			'value'             => get_post_meta( $variation->ID, 'elex_wfp_desc_field', true ),
		)
	);

	woocommerce_wp_checkbox(
		array(
			'id'          => 'elex_wfp_enable_price[' . $variation->ID . ']',
			'desc'        => __( 'Enable', 'elex_wfp_flexible_price_domain' ),
			'desc_tip'    => __( 'Enable', 'elex_wfp_flexible_price_domain' ),
			'label'       => __( '  Hide Price', 'elex_wfp_flexible_price_domain' ),
			'description' => __( 'Display Regualar/Sale price', 'elex_wfp_flexible_price_domain' ),
			'value'       => get_post_meta( $variation->ID, 'elex_wfp_enable_price', true ),
		)
	);
	echo '</div>';
}

add_action( 'woocommerce_save_product_variation', 'elex_wfp_save_variation_settings_fields', 10, 2 );

function elex_wfp_save_variation_settings_fields( $post_id ) {
	if ( ! ( isset( $_REQUEST['elex-wfp-variable-field-nonce'] ) || wp_verify_nonce( sanitize_key( $_REQUEST['elex-wfp-variable-field-nonce'] ), 'woocommerce_save_data' ) ) ) {
		return false;
	}
	$text_field = isset( $_POST['elex_wfp_text_field'][ $post_id ] ) ? sanitize_text_field( $_POST['elex_wfp_text_field'][ $post_id ] ) : '';
	update_post_meta( $post_id, 'elex_wfp_text_field', esc_attr( $text_field ) );
	 
	$checkbox = isset( $_POST['elex_wfp_checkbox'][ $post_id ] ) ? sanitize_text_field( $_POST['elex_wfp_checkbox'][ $post_id ] ) : '';
	update_post_meta( $post_id, 'elex_wfp_checkbox', $checkbox );
	
	$label_field = isset( $_POST['elex_wfp_label_field'][ $post_id ] ) ? sanitize_text_field( $_POST['elex_wfp_label_field'][ $post_id ] ) : '';
	update_post_meta( $post_id, 'elex_wfp_label_field', esc_attr( $label_field ) );
   
	$desc_field = isset( $_POST['elex_wfp_desc_field'][ $post_id ] ) ? sanitize_text_field( $_POST['elex_wfp_desc_field'][ $post_id ] ) : '';
	update_post_meta( $post_id, 'elex_wfp_desc_field', esc_attr( $desc_field ) );

	$enable_price_checkbox = isset( $_POST['elex_wfp_enable_price'][ $post_id ] ) ? sanitize_text_field( $_POST['elex_wfp_enable_price'][ $post_id ] ) : '';
	update_post_meta( $post_id, 'elex_wfp_enable_price', $enable_price_checkbox );
   
}

// Add New Variation Settings
add_filter( 'woocommerce_available_variation', 'elex_wfp_load_variation_settings_fields' );
function elex_wfp_load_variation_settings_fields( $variations ) {
		$variations['text_field']     = get_post_meta( $variations['variation_id'], 'elex_wfp_text_field', true );
		$varition['label_field']      = get_post_meta( $variations['variation_id'], 'elex_wfp_label_field', true );
		$varition['desc_field']       = get_post_meta( $variations['variation_id'], 'elex_wfp_desc_field', true );
		$varition['price_hide_field'] = get_post_meta( $variations['variation_id'], 'elex_wfp_enable_price', true );
	return $variations;
}

