<?php
/**
 * Dinkum Interactive.
 *
 * @package WC_HPC
 */

/**
 * Check availability.
 *
 * @param bool   $show            Show content.
 * @param string $customer_email  Customer email to check.
 * @param int    $customer_id     Customer ID to check.
 * @param int    $product_id      Product ID to check.
 *
 * @return bool
 */
function wc_hpc_check_availability( $show, $customer_email, $customer_id, $product_id ) {

	if ( $product_id ) {

		$user = false;

		if ( $customer_id ) {

			$user = get_user_by( 'id', $customer_id );

		} elseif ( ! $customer_email && ! $customer_id ) {

			$user = wp_get_current_user();
		}

		if ( $user ) {

			$customer_email = $user->user_email;
			$customer_id    = $user->ID;
			$show           = wc_customer_bought_product( $customer_email, $customer_id, $product_id );
		}
	} else {

		$show = true;
	}

	return $show;
}
add_filter( 'wc_hpc_show_content', 'wc_hpc_check_availability', 10, 4 );

