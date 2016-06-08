<?php

class IgniteWoo_Wishlist_Settings extends IGN_Integration {

	function __construct() {

		$this->id = 'ignitewoo_wishlists';

		$this->method_title = __( 'IgniteWoo Wishlists', 'ignitewoo-wishlists-pro' );

		$this->method_description = __( 'Adjust the settings to suit your needs', 'ignitewoo-wishlists-pro' );

		$this->init_form_fields();

		$this->init_settings();

		add_action( 'woocommerce_update_options_integration_' . $this->id , array( &$this, 'process_admin_options') );
		
		// WC 2.1
		add_action( 'woocommerce_settings_save_ignitewoo', array( &$this, 'process_admin_options') );

		add_action( 'admin_head', array( &$this, 'admin_head' ) );

	}

	
	function admin_head() {

		if ( isset( $_GET['page'] ) && isset( $_GET['section'] )
			&& 'woocommerce_settings' == $_GET['page']
			&& 'ignitewoo_wishlists' == $_GET['section'] ) {

		?>
		<style>
			#mainform h4 { background: none repeat scroll 0 0 #EFEFEF; padding: 5px 0 5px 5px; }
			#woocommerce_extensions { display:none !important; }
		</style>

		<?php
		}
	}


	function init_form_fields() {

		$this->form_fields = array(
				'form_position_title' => array(
					'title' 	=> __( 'Settings', 'ignitewoo-wishlists-pro'),
					'type'		=> 'title',
				),
				'allow_guests' => array(
					'title' 	=> __( 'Allow Guest Wishlists', 'ignitewoo-wishlists-pro'),
					'description'   => __( 'Allow non-logged in users to create wishlists. NOTE that guest wishlists do not include the ability for people to buy an item for the wishlist owner.', 'ignitewoo-wishlists-pro'),
					'label' 	=> __( 'Enable', 'ignitewoo-wishlists-pro'),
					'type' 		=> 'checkbox',
					'default' 	=> 'no',
				),
				'button_or_link' => array(
					'title' 	=> __( 'Button or Link', 'ignitewoo_auctions'),
					'description' 	=> __( 'Select the Add to Wishlist style to use on product pages', 'ignitewoo_auctions'),
					'type' 		=> 'select',
					'default' 	=> 'button',
					'options'	=> array(
								'button' => 'Button',
								'link' => 'Link Only',
								'gift-icon-white-red' => 'Link w/White box & red ribbon',
								'gifts-icon-blue-gold' => 'Link w/blue box & gold ribbon',
								'gift-icon-red-yellow' => 'Link w/red box & yellow ribbon',
								'gift-icon-red-green' => 'Link w/red box & green ribbon',
								'gift-icon-gold-gold' => 'Link w/gold box & gold ribbon',
								'gift-icon-black-teal' => 'Link w/black box & teal ribbon',
								'gift-icon-black-silver' => 'Link w/black box & silver ribbon',
								'gift-icon-star-1' => 'Gold Star w/outline',
								'gift-icon-star-2' => 'Gold Star - no outline',
								'gift-icon-star-red' => 'Red Star',
								'gift-icon-star-orange' => 'Orange Star',
								'gift-icon-star-green' => 'Green Star',
								'gift-icon-star-purple' => 'Purple Star',
								'gift-icon-star-blue' => 'Blue Star',
								'gift-icon-star-white' => 'White Star',
								'gift-icon-star-black' => 'Black Star',
								

								
							)
				),
				'button_link_text' => array(
					'title' 	=> __( 'Button or Link Text', 'ignitewoo-wishlists-pro'),
					'description'   => __( '', 'ignitewoo-wishlists-pro'),
					'type' 		=> 'text',
					'default' 	=> 'Add to Wishlist',
				),
				'in_list_text' => array(
					'title' 	=> __( 'In Your List', 'ignitewoo-wishlists-pro'),
					'description'   => __( 'Text to display on the product page when the item is in one of the user\'s wishlists', 'ignitewoo-wishlists-pro'),
					'type' 		=> 'text',
					'default' 	=> 'Item is in one or more your wishlists:',
				),
		);

	}

}


