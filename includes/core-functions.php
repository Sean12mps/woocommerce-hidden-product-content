<?php
/**
 * Dinkum Interactive.
 *
 * @package WC_HPC
 */

/**
 * Hidden content.
 *
 * @param string $customer_email  Customer email to check.
 * @param int    $customer_id     Customer ID to check.
 * @param int    $product_id      Product ID to check.
 * @param string $content         Content.
 *
 * @return void
 */
function wc_hpc_content( $customer_email = '', $customer_id = '', $product_id = '', $content = '' ) {

	$show_content = apply_filters( 'wc_hpc_show_content', false, $customer_email, $customer_id, $product_id );

	if ( $show_content ) {

		do_action( 'wc_hpc_template_content_shown', $customer_email, $customer_id, $product_id, $content );

	} else {

		do_action( 'wc_hpc_template_content_hidden', $customer_email, $customer_id, $product_id, $content );
	}
}


