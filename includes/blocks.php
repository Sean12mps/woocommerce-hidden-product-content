<?php
/**
 * Dinkum Interactive.
 *
 * @package WC_HPC
 */

/**
 * Register block types
 */
function wc_hpc_acf_block_types() {
	acf_register_block_type(
		array(
			'name'            => 'woocommerce-hidden-product-content',
			'title'           => __( 'Woo Hidden Product Content', 'wc_hpc' ),
			'description'     => __( 'add Artist listing block', 'wc_hpc' ),
			'render_callback' => 'wc_hpc_acf_block',
			'icon'            => 'list-view',
			'keywords'        => array( 'hidden', 'hidden content' ),
		)
	);
}

/**
 * Register block types
 *
 * @param   array  $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool   $is_preview True during AJAX preview.
 * @param   mixed  $post_id The post ID this block is saved to.
 *
 * @return void
 */
function wc_hpc_acf_block( $block, $content = '', $is_preview = false, $post_id = 0 ) {

	$current_user   = wp_get_current_user();
	$customer_email = $current_user->user_email;
	$customer_id    = $current_user->ID;
	$product_id     = get_field( 'product_id' );
	$content        = get_field( 'content' );

	if ( $product_id ) {

		$product = wc_get_product( $product_id );

		if ( $product && method_exists( $product, 'get_ID' ) ) {

			$product_id = $product->get_ID();

			wc_hpc_content( $customer_email, $customer_id, $product_id, $content );
		}
	}
}

// Check if function exists and hook into setup.
if ( function_exists( 'acf_register_block_type' ) ) {
	add_action( 'acf/init', 'wc_hpc_acf_block_types' );
}
