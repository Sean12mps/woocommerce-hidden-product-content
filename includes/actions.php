<?php
/**
 * Dinkum Interactive.
 *
 * @package WC_HPC
 */

/**
 * Show content.
 *
 * @param string $customer_email  Customer email to check.
 * @param int    $customer_id     Customer ID to check.
 * @param int    $product_id      Product ID to check.
 * @param string $content         Content.
 *
 * @return void
 */
function wc_hpc_template_show_content( $customer_email, $customer_id, $product_id, $content ) {

	include WC_HPC_DIR_TMP . '/content-show.php';
}
add_action( 'wc_hpc_template_content_shown', 'wc_hpc_template_show_content', 10, 4 );

/**
 * Hide content.
 *
 * @param string $customer_email  Customer email to check.
 * @param int    $customer_id     Customer ID to check.
 * @param int    $product_id      Product ID to check.
 * @param string $content         Content.
 *
 * @return void
 */
function wc_hpc_template_hide_content( $customer_email, $customer_id, $product_id, $content ) {

	include WC_HPC_DIR_TMP . '/content-hide.php';
}
add_action( 'wc_hpc_template_content_hidden', 'wc_hpc_template_hide_content', 10, 4 );

/**
 * Register scripts.
 *
 * @return void
 */
function wc_hpc_register_scripts() {

	wp_register_script(
		'wc_hpc_scripts',
		WC_HPC_DIR_JS . '/wc-hpc-scripts.js',
		array( 'jquery' ),
		'1.0.0',
		true
	);
}
add_action( 'wp_enqueue_scripts', 'wc_hpc_register_scripts', 5 );

/**
 * Print scripts.
 *
 * @return void
 */
function wc_hpc_enqueue_scripts() {

	if ( is_woocommerce() ) {

		wp_enqueue_script( 'wc_hpc_scripts' );

		$variables = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'wc_hpc_nonce' ),
		);

		wp_localize_script( 'wc_hpc_scripts', 'wc_hpc_vars', $variables );
	}
}
add_action( 'wp_enqueue_scripts', 'wc_hpc_enqueue_scripts', 10 );

/**
 * Print scripts.
 *
 * @return void
 */
function wc_hpc_login() {

	$nonce = isset( $_REQUEST['wc_hpc_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['wc_hpc_nonce'] ) ) : false;

	if ( wp_verify_nonce( $nonce, 'wc_hpc_nonce' ) ) {

		$customer_email     = wc_hpc_get_param( 'email', $nonce );
		$product_id         = wc_hpc_get_param( 'product_id', $nonce );
		$has_bought_product = wc_customer_bought_product( $customer_email, '', $product_id ) ? true : false;
		$message            = __( 'Server is busy, please try again later', 'wchpc' );

		if ( $has_bought_product ) {

			// Check if user exist.
			$user = get_user_by( 'email', $customer_email );

			if ( $user ) {

				$account_page_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );

				$message = wp_sprintf( 'You\'re already a member. Please <a href="%s">login</a>.', $account_page_url );

			} else {

				// Create user then send key.
				$user_id = wp_create_user( $customer_email, '', $customer_email );

				if ( $user_id ) {

					$user = get_user_by( 'id', $user_id );

					if ( $user ) {

						$key = get_password_reset_key( $user );

						update_user_meta( $user->ID, 'wchpc_temporary_user', $key );

						if ( ! is_wp_error( $key ) ) {

							$login_url = add_query_arg(
								array(
									'wc_hpc_key'   => $key,
									'wc_hpc_email' => rawurlencode( $user->user_email ),
									'wc_hpc_pid'   => $product_id,
								),
								get_permalink( $product_id )
							);

							$headers = array(
								'From: Admin <no-reply@' . wp_parse_url( get_site_url(), PHP_URL_HOST ) . '>',
								'Content-type: text/html; charset=utf-8',
							);

							$mail_title = 'Hidden Content for Product: ' . $product_id;

							$message = 'You may use this link to view the hidden content. <a href="' . $login_url . '">' . $login_url . '</a>';

							$mail = wp_mail( $user->user_email, $mail_title, $message, $headers );

							if ( $mail ) {

								$message = __( 'We sent a message to your email, please review to see the content.', 'wchpc' );

							} else {

								$message = __( 'We are unable to send you an email, please contact support.', 'wchpc' );
							}
						}
					}
				}
			}
		} else {

			$message = __( 'You haven\'t purchased the product yet.', 'wchpc' );
		}

		$response = array(
			'valid'   => $has_bought_product,
			'message' => $message,
		);

		wp_send_json( $response );
	}

	wp_die();
}
add_action( 'wp_ajax_nopriv_wc_hpc_login', 'wc_hpc_login', 10 );

/**
 * Handle key login.
 *
 * @return void
 */
function wc_hpc_template_redirect() {

	if (
		is_singular( 'product' ) &&
		isset( $_GET['wc_hpc_key'] ) &&
		isset( $_GET['wc_hpc_email'] ) &&
		isset( $_GET['wc_hpc_pid'] )
	) {

		$key   = sanitize_text_field( wp_unslash( $_GET['wc_hpc_key'] ) );
		$email = sanitize_text_field( wp_unslash( $_GET['wc_hpc_email'] ) );
		$pid   = sanitize_text_field( wp_unslash( $_GET['wc_hpc_pid'] ) );

		$user = get_user_by( 'email', $email );

		if ( $user ) {

			$check = check_password_reset_key( $key, $user->user_login );

			if ( ! is_wp_error( $check ) ) {

				wp_set_auth_cookie( $check->data->ID, true, false );

				wp_safe_redirect( get_permalink( $pid ) );

				exit;
			}
		}
	}
}
add_action( 'template_redirect', 'wc_hpc_template_redirect', 10 );

/**
 * Get request param.
 *
 * @param string $name  Name of the param.
 * @param string $nonce Nonce key to check.
 *
 * @return mixed
 */
function wc_hpc_get_param( $name, $nonce ) {

	$val = null;

	if ( isset( $_REQUEST[ $name ] ) && wp_verify_nonce( $nonce, 'wc_hpc_nonce' ) ) {

		$val = sanitize_text_field( wp_unslash( $_REQUEST[ $name ] ) );
	}

	return $val;
}

/**
 * Add login referer.
 *
 * @return void
 */
function wc_hpc_add_login_referer() {

	$referer = wc_get_raw_referer();

	if ( $referer ) {

		$referer  = wp_sanitize_redirect( trim( $referer, " \t\n\r\0\x08\x0B" ) );
		$location = wp_validate_redirect( $referer, wc_get_page_permalink( 'myaccount' ) );

		if ( $location ) {
			echo wp_sprintf( '<input type="hidden" name="redirect" value="%s">', $location );
		}
	}
}
add_action( 'woocommerce_login_form_start', 'wc_hpc_add_login_referer', 10 );

