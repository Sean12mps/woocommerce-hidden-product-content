<?php
/**
 * Dinkum Interactive.
 *
 * @package WC_HPC
 */

$account_page_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );

?>

<?php if ( is_user_logged_in() ) : ?>
	<p>Content will be shown here after purchase</p>
<?php else : ?>
	<p>
		<strong>This product contains a hidden content</strong>
		<br>
		If you already purchased this product as a member, please <a href="<?php echo esc_url( $account_page_url ); ?>">login</a> to gain access.
		<br>
		If you purchased it without being a member, please enter your email below to view the content.
	</p>
	<div class="wc_hpc_email_login">
		<div class="wc_hpc_login_form">
			<label for="customer-email"><strong>Email</strong></label>
			<br>
			<input type="email" name="wc_hpc-customer_email" required>
			<input type="hidden" name="wc_hpc-product_id" value="<?php echo esc_attr( get_the_ID() ); ?>">
		</div>
		<div class="wc_hpc_login_action">
			<p></p>
			<button class="wc_hpc_submit">Request Access</button>
		</div>
	</div>
<?php endif; ?>
