<?php

if ( !defined( 'ABSPATH' ) ) 
	die( '404 - Not Found' );

/**
 * IgniteWoo Integration class
 *
 * Based on WC Integration class
 *
 * Extended by individual IgniteWoo integrations to offer additional functionality.
 *
 */
abstract class IGN_Integration extends WC_Settings_API {

	/**
	 * Admin Options
	 *
	 * Setup the settings on the screen
	 *
	 */
	function admin_options() { ?>

		<h3><?php echo isset( $this->method_title ) ? $this->method_title : __( 'Settings', 'woocommerce' ) ; ?></h3>

		<?php echo isset( $this->method_description ) ? wpautop( $this->method_description ) : ''; ?>

		<table class="form-table">
			<?php $this->generate_settings_html(); ?>
		</table>

		<!-- Section -->
		<div><input type="hidden" name="section" value="<?php echo $this->id; ?>" /></div>

		<?php
	}


}
