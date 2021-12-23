<?php

/*****************
 * Script control
 *****************/


add_action( 'wp_enqueue_scripts', 'elex_cpp_load_assets' );
add_action( 'admin_enqueue_scripts', 'elex_cpp_load_assets' );

function elex_cpp_load_assets() {

	global $woocommerce;
	$woocommerce_version = function_exists( 'WC' ) ? WC()->version : $woocommerce->version;
	wp_enqueue_style( 'elex-cpp-plugin-bootstrap', plugins_url( '/assets/css/bootstrap.css', dirname( __FILE__ ) ), array( 'jquery' ), $woocommerce_version );
	wp_enqueue_style( 'elex-cpp-plugin-styles', plugins_url( '/assets/css/plugin-styles.css', dirname( __FILE__ ) ), array( 'jquery' ), $woocommerce_version );

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'elex-cpp-custom-jquery', plugins_url( '/assets/js/plugin-scripts.js', dirname( __FILE__ ) ), array( 'jquery' ), $woocommerce_version, false );
}
