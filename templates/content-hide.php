<?php
/**
 * Dinkum Interactive.
 *
 * @package WC_HPC
 */

$account_page_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );

// Notice for customer.
$_notice_for_customer = wp_sprintf( '<p>%s</p>', __( 'Content will be shown here after purchase', 'wc_hpc' ) );
$notice_for_customer  = get_field( 'wc_hpc_notice_for_customer', 'option' );
$notice_for_customer  = $notice_for_customer ? $notice_for_customer : $_notice_for_customer;

// Notice for users.
ob_start();
?>
<p>
	<strong>This product contains a hidden content</strong>
	<br>
	If you already purchased this product as a member, please <a href="<?php echo esc_url( $account_page_url ); ?>">login</a> to gain access.
	<br>
	If you purchased it without being a member, please enter your email below to view the content.
</p>
<?php
$_notice_for_users = ob_get_clean();
$notice_for_users  = get_field( 'wc_hpc_notice_for_users', 'option' );
$notice_for_users  = $notice_for_users ? $notice_for_users : $_notice_for_users;

// Button label.
$_button_label = __( 'Request Access', 'wc_hpc' );
$button_label  = get_field( 'wc_hpc_emailme_button_label', 'option' );
$button_label  = $button_label ? $button_label : $_button_label;


?>

<?php if ( is_user_logged_in() ) : ?>

	<?php echo $notice_for_customer; //phpcs:ignore ?>

<?php else : ?>

	<?php echo $notice_for_users; //phpcs:ignore ?>

	<div class="wc_hpc_email_login">
		<div class="wc_hpc_login_form">
			<label for="customer-email"><strong><?php esc_html_e( 'Email', 'wc_hpc' ); ?></strong></label>
			<br>
			<input type="email" name="wc_hpc-customer_email" required>
			<input type="hidden" name="wc_hpc-product_id" value="<?php echo esc_attr( get_the_ID() ); ?>">
		</div>
		<div class="wc_hpc_login_action">
			<button class="wc_hpc_submit"><?php echo esc_html( $button_label ); ?></button>
		</div>
	</div>
<?php endif; ?>
