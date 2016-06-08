<?php
/*

This templated is loaded ONCE from one of the following locations, listed in order below: 

    - Your theme directory's WooCommerce template subdirectory.
 
    - Your theme directory

    - In WooCommerce Gift Certificate plugin's directory


*/
?>

<?php if (!defined('ABSPATH')) die; ?>

<?php 

global $buyer, $title, $url, $order_id, $gift_cert_ids, $woocommerce; $order = &new woocommerce_order( $order_id ); 

$currency_symbol = get_woocommerce_currency_symbol();

?>

<?php do_action('woocommerce_email_header'); ?>

<p><?php _e("Someone purchased an item on your gift list!:", 'ignitewoo-wishlists-pro'); ?></p>

<?php do_action('woocommerce_email_before_order_table', $order, false); ?>

<h3><?php echo __('Order #:', 'ignitewoo-wishlists-pro') . ' ' . $order_id; ?></h3>

<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
        <thead>
                <tr>
                        <th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e('Product Name', 'ignitewoo-wishlists-pro'); ?></th>
                        <th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e('Buyer', 'ignitewoo-wishlists-pro'); ?></th>
                </tr>
        </thead>
        <tbody>

		<?php foreach( $gift_cert_ids as $cid ) { ?>

			<tr>
				<td><a href="<?php echo $url ?>"><?php echo $title ?></a></td>
				<td><?php echo $buyer ?></td>
			</tr>

		<?php } ?>

        </tbody>

</table>


<div style="clear:both;"></div>

<?php do_action('woocommerce_email_footer'); ?>