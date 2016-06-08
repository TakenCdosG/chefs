<?php
/*

This templated is loaded from one of the following locations, listed in order below: 

    - From your theme directory's "woocommerce/emails" subdirectory.

    - From your WooCommerce Wishlists plugin "emails" subdirectory

*/
?>

<?php 

if ( !defined( 'ABSPATH' ) ) die;

global $followup_content, $email_heading, $wishlist_item;

?>

<?php do_action( 'woocommerce_email_header', $email_heading ); ?>

	<p><?php _e( 'Someone purchased an item from your wishlist!', 'ignitewoo-wishlists-pro' ); ?></p>

	<table border="0" cellpadding="5" cellspacing="0" width="600">
		<tr>
			<td><?php echo __( 'Item:', 'ignitewoo-wishlists-pro' ) . ' ' . $wishlist_item ?></td>
		</tr>
		<tr>
			<td><?php _e( 'Message from buyer:', 'ignitewoo-wishlists-pro' ) ?></td>
		</tr>
		<tr>
			<td><?php echo $followup_content ?></td>
		</tr>
	</table>

	<div style="clear:both;"></div>

	<hr/>

<?php do_action( 'woocommerce_email_footer' ); ?>