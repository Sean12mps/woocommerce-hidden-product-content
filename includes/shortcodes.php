<?php
/**
 * Dinkum Interactive.
 *
 * @package WC_HPC
 */

/**
 * Hidden content shortcode.
 *
 * @param array  $atts     Shortcode params.
 * @param string $content  Hidden content.
 * @return string
 */
function wc_hpc_shortcode( $atts, $content = '' ) {

	$current_user = wp_get_current_user();

	$atts = shortcode_atts(
		array(
			'customer_email' => $current_user->user_email,
			'customer_id'    => $current_user->ID,
			'product_id'     => '',
		),
		$atts,
		'wc_hpc'
	);

	if ( ! $atts['product_id'] ) {

		$product = wc_get_product( get_the_ID() );

		if ( $product && method_exists( $product, 'get_ID' ) ) {

			$atts['product_id'] = $product->get_ID();
		}
	}

	ob_start();

	wc_hpc_content( $atts['customer_email'], $atts['customer_id'], $atts['product_id'], $content );

	return ob_get_clean();
}
add_shortcode( 'wc_hpc', 'wc_hpc_shortcode' );

/**
 * Hidden content shortcode.
 *
 * @param array  $atts     Shortcode params.
 * @param string $content  Hidden content.
 * @return string
 */
function wc_hpc_add_to_cart_shortcode( $atts, $content = '' ) {

	$atts = shortcode_atts(
		array(
			'product_id' => '',
		),
		$atts,
		'wc_hpc'
	);

	$product = false;

	if ( $atts['product_id'] ) {

		$product = wc_get_product( $atts['product_id'] );

	} else {

		$product = wc_get_product( get_the_ID() );
	}

	if ( $product && method_exists( $product, 'get_ID' ) ) {

		return do_shortcode( "[add_to_cart id='{$product->get_ID()}']" );
	}
}
add_shortcode( 'wc_hpc_add_to_cart', 'wc_hpc_add_to_cart_shortcode' );
