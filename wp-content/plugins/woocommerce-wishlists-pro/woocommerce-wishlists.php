<?php
/*
Plugin Name: WooCommerce Wishlists Pro
Plugin URI:  http://ignitewoo.com
Description: Allows users to create any number of wishlists - public or private - and other people can buy items in a wishlist for the wishlist owner.
Version: 3.1.2
Author: Ignitewoo.com
Author URI: http://ignitewoo.com
*/ 

/**
ATTENTION TRANSLATORS:  This plugin uses the "woocommerce" text domain, so do your translations there. 
*/

class ignite_woocommerce_wishlist { 

	var $wishlist_items_in_cart = false;
	var $plugin_url = null;


	function __construct() {

		add_action( 'init', array( &$this, 'load_plugin_textdomain' ) );

		add_action( 'init', array( &$this, 'maybe_set_cookie' ) );
				
		// startup var init
		add_action( 'init', array( &$this, 'init' ), 9 );

		// register post type to store cert info
		add_action( 'init', array( &$this, 'register_wishlist_post_type' ), 9999 );

		add_action( 'init', array( &$this, 'maybe_unset_session_var' ), 9999999 );

		
		// add scripts to wishlist pages
		add_action( 'wp_head', array( &$this, 'wishlist_scripts'), 99999 );

		// queue the stylesheet
		add_action('wp_enqueue_scripts', array( &$this, 'wishlist_get_styles' ), 9999999 );

		// admin area wishlist view and edit panels, columns, sorting, etc
		add_filter( 'post_type_link', array( &$this, 'wishlist_link_filter' ), 1, 3 );
		add_filter( 'manage_edit-custom_wishlists_columns', array( &$this, 'add_wishlist_columns' ) );
		add_action( 'manage_custom_wishlists_posts_custom_column', array( &$this, 'manage_wishlist_columns' ), 10, 2);
		add_action( 'restrict_manage_posts', array( &$this, 'restrict_by_wishlist' ) );
		add_filter( 'parse_query', array( &$this, 'convert_wishlist_id_to_taxonomy_term_in_query' ) );
		add_filter( 'manage_edit-custom_wishlists_sortable_columns', array( &$this, 'sortable_columns' ) );
		add_action( 'add_meta_boxes', array( &$this, 'metaboxes' ) );
		add_action( 'save_post', array( &$this, 'save_post' ), 50 );
	
		// insert scripts to admin area
		add_action( 'wp_head', array( &$this, 'wp_head' ), 99 );

		add_action( 'woocommerce_after_cart_contents', array( &$this, 'after_cart_contents'), -1 ); 

		add_action( 'woocommerce_init', array( &$this, 'woocommerce_init' ), 10 );

		 // hook into my account page to display wishlists
                add_action( 'woocommerce_after_my_account', array( &$this, 'my_wishlists'), -999 );

		add_shortcode( 'my_wishlists', array( &$this, 'shortcode_my_wishlists') );

		add_action( 'woocommerce_checkout_shipping', array( &$this, 'product_receiver_detail_form' ), 1000, 5 );
		add_action( 'woocommerce_before_checkout_process', array( &$this, 'verify_wishlist_receiver_details' ) );
		add_action( 'woocommerce_new_order', array( &$this, 'add_wishlist_receiver_details_in_order' ) );

		add_action( 'the_content', array( &$this, 'wishlist_the_content' ), 1, 1 );

		// hook to insert add to cart button
		
		add_action( 'woocommerce_after_add_to_cart_form', array( &$this, 'add_to_wishlist_button' ), 10, 2 );

		// Record data when a new order is placed
		add_action( 'woocommerce_checkout_order_processed', array( &$this, 'wishlist_new_order' ),  99999, 2 );

		add_filter( 'woocommerce_screen_ids', array( $this, 'screen_ids' ) );
		
		// hook into woocommerce to record user purchase in user's meta data
		add_action( 'woocommerce_order_status_completed', array( &$this, 'wishlist_completed_purchase' ),  1, 1 );
		add_action( 'woocommerce_order_status_processing', array(&$this, 'wishlist_completed_purchase'), 19, 1 );
		add_action( 'woocommerce_order_status_refunded', array(&$this, 'wishlist_cancel_purchase'), 19, 1 );
		add_action( 'woocommerce_order_status_cancelled', array(&$this, 'wishlist_cancel_purchase'), 19, 1 );
		add_action( 'woocommerce_order_status_on-hold', array(&$this, 'wishlist_cancel_purchase'), 19, 1 );
		add_action( 'woocommerce_order_status_failed', array(&$this, 'wishlist_cancel_purchase'), 19, 1 );
		add_action( 'woocommerce_order_status_pending', array(&$this, 'wishlist_cancel_purchase'), 19, 1 );

		// send the notice info to the recipient AFTER the transaction is marked as complete
		add_action('woocommerce_order_status_completed', array( &$this, 'wishlist_completed_order_customer_notification' ), 2, 1 );

		// Order meta display
		add_action( 'woocommerce_after_order_itemmeta', array( &$this, 'wishlist_item_meta' ), 1, 3 );
		
		// hook to remove coupon_code if submitted in conjunction with wishlist_code
		//add_action( 'init', array( &$this, 'check_for_wishlist_processing'), -9999);

		//Ajax for updating / creating / deleting / managing wishlists
		add_action('wp_ajax_wishlist_action', array( &$this, 'wishlist_action_callback'), 1 );
		add_action('wp_ajax_wishlist_add_action', array( &$this, 'wishlist_add_action_callback'), 1 );
		add_action('wp_ajax_wishlist_remove_item', array( &$this, 'wishlist_remove_item_callback'), 1 );
		add_action('wp_ajax_wishlist_delete', array( &$this, 'wishlist_delete_callback'), 1 );
		add_action('wp_ajax_wishlist_buy_item', array( &$this, 'wishlist_buy_item_callback'), 1 );

		add_action('wp_ajax_nopriv_wishlist_action', array( &$this, 'wishlist_action_callback'), 1 );
		add_action('wp_ajax_nopriv_wishlist_add_action', array( &$this, 'wishlist_add_action_callback'), 1 );
		add_action('wp_ajax_nopriv_wishlist_remove_item', array( &$this, 'wishlist_remove_item_callback'), 1 );
		add_action('wp_ajax_nopriv_wishlist_delete', array( &$this, 'wishlist_delete_callback'), 1 );
		add_action('wp_ajax_nopriv_wishlist_buy_item', array( &$this, 'wishlist_buy_item_callback'), 1 );

		add_action( 'wp_ajax_admin_add_wishlist_item', array( &$this, 'admin_add_wishlist_item' ), 1 );
		add_action( 'wp_ajax_wishlist_get_customer_details', array( &$this, 'wishlist_get_customer_details' ), 1 );
		
		// filter wishlists during search
		add_action( 'the_posts', array( &$this, 'filter_wishlists' ), 1, 1);

		// custom search forms
		add_action( 'wishlist_search', array( &$this, 'wishlist_search' ) );

		$this->plugin_url = null;

	}


	function init() { 
	
		if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '<=' ) )
			add_filter( 'woocommerce_in_cart_product_title', array( &$this, 'product_title' ), 999, 3 );
		else 
			add_filter( 'woocommerce_cart_item_name', array( &$this, 'product_title' ), 999, 3 );
	}
	
	function maybe_set_cookie() {

		// cookie expires in 5 years
		$expires = time() + 60 * 60 * 24 * 365 * 5;
		$random_id = microtime( true );
		$random_id = str_replace( '.', '', $random_id );
		
		if ( !isset( $_COOKIE['wishlist_user_id'] ) && empty( $_COOKIE['wishlist_user_id'] ) )
			setcookie( 'wishlist_user_id', $random_id, $expires, COOKIEPATH, COOKIE_DOMAIN );
	}

	function woocommerce_init() {

		if ( is_user_logged_in() )
			return;
			
		if ( !isset( $_COOKIE['wishlist_user_id'] ) && empty( $_COOKIE['wishlist_user_id'] ) )
			return;

		remove_shortcode( 'woocommerce_my_account' );
		
		add_shortcode( 'woocommerce_my_account', array( $this, 'output' ) );
	}

	public static function output( $atts ) {
		global $woocommerce, $woocom_wishlist;

		$woocommerce->nocache();

		$woocom_wishlist->wishlist_get_template( 'guest-account.php' );

		woocommerce_get_template( 'myaccount/form-login.php' );
	}
	
	function load_plugin_textdomain() {
	
		$locale = apply_filters( 'plugin_locale', get_locale(), 'ignitewoo-wishlists-pro' );

		// Allow upgrade safe, site specific language files in /wp-content/languages/woocommerce-subscriptions/
		load_textdomain( 'ignitewoo-wishlists-pro', WP_LANG_DIR.'/woocommerce/ignitewoo-wishlists-pro-'.$locale.'.mo' );

		$plugin_rel_path = apply_filters( 'ignitewoo_translation_file_rel_path', dirname( plugin_basename( __FILE__ ) ) . '/languages' );

		// Then check for a language file in /wp-content/plugins/woocommerce-subscriptions/languages/ (this will be overriden by any file already loaded)
		load_plugin_textdomain( 'ignitewoo-wishlists-pro', false, $plugin_rel_path );

	}

	public function plugin_url() {
	
		if ( $this->plugin_url )
			return $this->plugin_url;
			
		return $this->plugin_url = plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) );
	}

	
	function maybe_unset_session_var() { 
		global $woocommerce;

		if ( version_compare( WOOCOMMERCE_VERSION, '2.3', '>=' ) )
			return;
			
		if ( empty( $woocommerce->cart->cart_contents ) )
			$this->session_set( 'wishlist', '' );
			//$_SESSION['wishlist'] = '';

	}


	// insert Javascript needed for the wishlist popup forms on the public side 
	function wp_head() { 
	?>
		<script>

		jQuery( document ).ready( function( $ ) { 

			jQuery("#wishlist_button").bind( 'click', function() {

				var args = jQuery( this ).attr( 'rel' );

				if ( !args ) return;

				pid_uid = args.split( ',' );

				if ( !pid_uid[0] || !pid_uid[1] ) return;


				if ( jQuery( "input[name='variation_id']" ).length > 0 ) {

					vid = jQuery( "input[name='variation_id']" ).val();

					if ( null == vid || 0 == vid.length ) { 
						alert( "<?php _e( 'Select an option first', 'ignitewoo-wishlists-pro' ) ?>" );
						return false;

					} 

				} else { 

					vid = "";
				}

				qty = jQuery( ".qty" ).val();

				if ( null == qty || qty.length <= 0 )
					qty = 0;
					
				
				//jQuery.fancybox.showActivity();

				<?php echo 'nonce = "' . wp_create_nonce  ( 'wishlist_nonce' ) . '";' ?>

				function handleFormValitations(){

					var form = $("form#wishslist_entry_form");
			        var validate_rules = {
			           'event-type': {
			                required: true,
			           },
			           'wishlist_title': {
			           		required: true,
							lettersonly: true,
			            },   
			           'co-registrant-name': {
							lettersonly: true,
			            },    
			           'co-registrant-email': {
							email: true,
			            },    
			        };	

					var validate_messages = {
			           'wishlist_title': {
							lettersonly: 'No special characters are allowed',
			            },
			           'co-registrant-name': {
							lettersonly: 'No special characters are allowed',
			            },    
				    };

			        $.validator.addMethod("lettersonly",
			            function(value, element) {
			              return this.optional(element) || /^([a-z ÃƒÆ’Ã‚Â±ÃƒÆ’Ã‚Â¡ÃƒÆ’Ã‚Â£ÃƒÆ’Ã‚Â¢ÃƒÆ’Ã‚Â¤ÃƒÆ’ ÃƒÆ’Ã‚Â©ÃƒÆ’Ã‚ÂªÃƒÆ’Ã‚Â«ÃƒÆ’Ã‚Â¨ÃƒÆ’Ã‚Â­ÃƒÆ’Ã‚Â®ÃƒÆ’Ã‚Â¯ÃƒÆ’Ã‚Â¬ÃƒÆ’Ã‚Â³ÃƒÆ’Ã‚ÂµÃƒÆ’Ã‚Â´ÃƒÆ’Ã‚Â¶ÃƒÆ’Ã‚Â²ÃƒÆ’Ã‚ÂºÃƒÆ’Ã‚Â»ÃƒÆ’Ã‚Â¼ÃƒÆ’Ã‚Â¹ÃƒÆ’Ã‚Â§]{2,60})$/i.test(value);
			        });	

			        form.validate({
			            rules: validate_rules,
			            messages: validate_messages,
			        });

			        console.log("> Validando.");
			        return form.valid();
				}

				$("#wishlist_add_hidden_link").prettyPhoto({
					hook: 'data-rel',
					social_tools: false,
					theme: 'pp_woocommerce',
					horizontal_padding: 20,
					opacity: 0.8,
					deeplinking: false,
					default_height: '200px',
					modal: true,	
					changepicturecallback: function() {

						$( 'button#wishlist_add_button' ).on( 'click', function() {
							if(handleFormValitations()){
								var args = jQuery('form#wishslist_entry_form').serialize();
								jQuery.ajax({
									type	: "POST",
									cache	: false,
									url		: "<?php echo admin_url( 'admin-ajax.php' ) ?>",
									data	: args,
									success: function(data) {
										$( '.pp_overlay' ).trigger( 'click' );
										setTimeout( function() { 
											$( 'div#wishlist_box_wrapper' ).html( data );
											$( '#wishlist_add_hidden_link' ).trigger( 'click' );
										}, 1000 );
									}
								});
							}
							return false;
						});

						$( 'input.wishlist_radio_toggle' ).on( 'click', function() {

							if ( 1 == jQuery( '.wishlist_radio_toggle:checked' ).val() )
								jQuery( '#wishlist_title_field').attr( 'disabled', 'disabled' );


							if ( 2 == jQuery( '.wishlist_radio_toggle:checked' ).val() )
								jQuery('#wishlist_existin_ul.li').attr( 'disabled', 'disabled' );

						});
						
						$( 'input.wishlist_radio_toggle' ).on( 'change', function() { 
							if ( 2 == $( this ).val() )
								$( '.wishlist_new_wrap' ).show();
							else 
								$( '.wishlist_new_wrap' ).hide();
						})
						
						// Fix for iLightBox used by Avada etc - hopefully
						setTimeout( function() {
							$( '.ilightbox-close' ).trigger( 'click' );
						}, 500)
					
					},
					callback: function() { 
						$("#wishlist_add_button").unbind( 'click' );
						$(".wishlist_radio_toggle").unbind( 'click' );
						$( '#wishlist_box_wrapper' ).html( '' );
					}
				});
					
				jQuery.ajax({
					type	: "POST",
					cache	: false,
					url		: "<?php echo admin_url( 'admin-ajax.php' ) ?>",
					data	: { nonce: nonce, user: pid_uid[1], prod: pid_uid[0], vid: vid, qty: qty, action: 'wishlist_action'  },
					success: function( data ) {
					
						$( '#wishlist_box_wrapper' ).html( data );		
						$( '#wishlist_add_hidden_link' ).trigger( 'click' );
					}
				});
				return false;
			});
		});
		</script> 
	<?php
	}


	// process ajax request from user to add to wishlist
	function wishlist_action_callback() { 
		global $post, $user_ID;

		$opts = get_option( 'woocommerce_ignitewoo_wishlists_settings' );

		if ( !isset( $opts['allow_guests'] ) )
			$opts['allow_guests'] = 'no';

		if ( 'yes' == $opts['allow_guests'] && !$user_ID && !isset( $_COOKIE['wishlist_user_id'] ) ) {
		
			?>
				<div class="wishlist_notice">

					<div class="wishlist_notice_wrap">

						<?php _e( 'Your browser is not accepting cookies', 'ignitewoo-wishlists-pro' ); ?>

						<div style="clear:both"></div>

						<div class="wishlist_login_register">

							<a href="<?php echo $myaccount ?>">
								<?php _e( 'You cannot use the wishlist feature', 'ignitewoo-wishlists-pro' ); ?>
							</a>

						</div>

					</div>

				</div>
			<?php
			
		} else if ( 'yes' !=  $opts['allow_guests'] && !$user_ID ) {

			if ( !$user_ID ) {

				$myaccount = woocommerce_get_page_id ( 'myaccount' );
				$myaccount = get_permalink( $myaccount );

				?>

				<div class="wishlist_notice">

					<div class="wishlist_notice_wrap">

						<?php _e( 'You must be logged in to use the wishlist feature', 'ignitewoo-wishlists-pro' ); ?>

						<div style="clear:both"></div>

						<div class="wishlist_login_register">

							<a href="<?php echo $myaccount ?>">
								<?php _e( 'Login or register', 'ignitewoo-wishlists-pro' ); ?>
							</a>

						</div>

					</div>

				</div>

				<?php

				die;

			}
			
		}
		
		if ( !isset( $_POST['nonce'] ) )
			die('no');

		if ( !wp_verify_nonce( $_POST['nonce'], 'wishlist_nonce' ) ) 
			die('fail');

		//echo 'hey: ' .  $_POST['user'] . ' ' .  $_POST['prod'];

		$wishlist_types = get_terms( 'c_wishlists_cat', '&hide_empty=0&order_by=id&order=asc' );

		remove_all_filters( 'pre_get_posts'  );
		remove_all_filters( 'the_posts' );
		remove_all_filters( 'wp' );
		
		$args = array( 
				'post_type' => 'custom_wishlists',
				'post_status' => 'publish',
				'order_by' => 'ID',
				'order'	=> 'ASC',
				'showposts' => 9999,
				'author' => absint( $_POST['user'] ),
			);

		$user_wishlists = new WP_Query( $args );

		if ( empty( $_POST['qty'] ) )
			$_POST['qty'] = 1;
		?>

		<h2 id="wishlist_title_bar" class="entry-title"><?php _e( 'Wishlists', 'ignitewoo-wishlists-pro' ) ?></h2>

		<form id="wishslist_entry_form" action="<?php admin_url( 'admin-ajax.php' ) ?>" method="post" >

		<input type="hidden" name="action" value="wishlist_add_action">
		<input type="hidden" class="user" name="u" value="<?php echo absint( $_POST['user'] )?>">
		<input type="hidden" class="prod" name="p" value="<?php echo absint( $_POST['prod'] )?>">
		<input type="hidden" class="vid" name="v" value="<?php echo absint( $_POST['vid'] )?>">
		<input type="hidden" class="vid" name="q" value="<?php echo absint( $_POST['qty'] )?>">
		<ul>

		<?php

		wp_nonce_field( 'add_to_wishlist' );

		if ( $user_wishlists->have_posts() ) { 

			echo '<li id="wishlist_existing_li">
				<h3 class="wishlist_h3_title" ><input class="wishlist_radio_toggle" checked="checked" type="radio" name="existing_or_new" value="1"> ' . __( 'Add Product to Existing Wishlist:', 'ignitewoo-wishlists-pro' ) . '</h3>
			    ';

			// NOTE TO THEMERS: This div "wishlists_list" ought to have CSS of overflow:auto; width: XXX; height: XXX
			echo '<div id="wishlists_list">
				<ul id="wishlist_existing_ul">';

			$i = 0;

			while ( $user_wishlists->have_posts() ) { 

				$user_wishlists->the_post();

				$wishlist_type = wp_get_post_terms( $post->ID, 'c_wishlists_cat', OBJECT );

				$i++;
				
				//if ( 1 == $i ) $checked = 'checked="checked"'; else $checked = '';
				$checked = '';
				
				echo '<li><label>
					<input ' . $checked . ' class="wishlist_radio_btn" type="radio" name="existing_wishlist" value="' . $post->ID . '">
				     ';
					the_title();

					if ( isset( $wishlist_type[0]->name ) )
						echo ' (' . $wishlist_type[0]->name . ')
				      </label></li>';

			}

			echo '</ul>
			    </div></li>';

		} 

		if ( $wishlist_types ) { 
			?>

			<li id="wishlist_new_li">

				<h3 class="wishlist_h3_title">
					<?php 
						$user_wishlists->rewind_posts(); 
						if ( $user_wishlists->have_posts() ) { 
					?>
						<input class="wishlist_radio_toggle" type="radio" name="existing_or_new" value="2">

					<?php } ?>

					<?php _e( 'Add Product to New Wishlist', 'ignitewoo-wishlists-pro' ) ?>

				</h3>

				<div class="wishlist_new_wrap" style="display:none">
				  <div class="row">
					<?php
					    $field_wishlist_type_key = "field_575726e432f3c";
					    $field_wishlist_type = get_field_object($field_wishlist_type_key);
					?>
					<div class="col-md-12">   
					   <div class="form-group form-group-event-type"">
					   	 <?php if( $field_wishlist_type ): ?>
					   		<label for="event-type">Event Type</label>
							<select name="event-type" id="event-type"s class="event-type">
								<option value="_none">- Select a value -</option>
							    <?php foreach( $field_wishlist_type['choices'] as $k => $v ): ?>
							    	<option value="<?php echo $k; ?>" <?php if($event_type == $k):?> selected <?php endif; ?> ><?php echo $v; ?></option>
							    <?php endforeach; ?>
							</select>
						<?php endif; ?>
					   </div>
						<!-- /input-group -->
					</div>

					<div class="col-md-12">   
					   <div class="form-group">
					     <label for="event-date">Event Date</label>
					     <input type="text" class="form-control" name="event-date" id="event-date" placeholder=""  value="">
					   </div>
						<!-- /input-group -->
					</div>

					<div class="col-md-12">   
					   <div class="form-group">
							<label class="wishlist_field_label"><?php _e( 'Wishlist Title', 'ignitewoo-wishlists-pro' ) ?></label>
							<input id="wishlist_title_field" type="text" name="wishlist_title" value="" size="45"> 
					   </div>
						<!-- /input-group -->
					</div>

					<div class="col-md-12">   
					   <div class="form-group">
					     <label for="co-registrant-name">Co-Registrant Name</label>
					     <input type="text" class="form-control" name="co-registrant-name" id="co-registrant-name" placeholder="" value="">
					   </div>
						<!-- /input-group -->
					</div>

					<div class="col-md-12">   
					   <div class="form-group">
					     <label for="co-registrant-email">Co-Registrant Email</label>
					     <input type="email" class="form-control" name="co-registrant-email" id="co-registrant-email" placeholder="" value="">
					   </div>
						<!-- /input-group -->
					</div>

					<div class="col-md-12">   
					   <div class="form-group">
							<div class="wishlist_type_label">
								<?php _e( 'Registry Type:', 'ignitewoo-wishlists-pro' ) ?></br>
							</div>
							<?php $i = 0; ?>

							<?php foreach ( $wishlist_types as $w ) { ?>
									<?php 
									if ( !is_user_logged_in() && 'private' == strtolower( $w->name ) ) 
										continue;
									?>	
								<?php $i++ ?>

								<label class="wishlist_field_label wishlist_type_btn"><input type="radio" class="wishlist_radio_btn" name="wishlist_num" value="<?php echo $w->term_id ?>" <?php if ( 3== $i ) echo 'checked="checked"';?> > <?php echo $w->name ?> (<em><?php echo $w->description ?></em>)</label>

							<?php } ?>
						</div>
						<!-- /input-group -->
					</div>
				  </div>
				</div>
			
				<button id="wishlist_add_button" class="button" type="button"><?php _e( 'Submit', 'ignitewoo-wishlists-pro' )?></button>
				
				<?php
				$user_wishlists->rewind_posts(); 
				if ( !$user_wishlists->have_posts() ) {
				?>
					<script>
					jQuery( '.wishlist_new_wrap' ).css( 'display', 'block' );
					</script>
				<?php 
				}
				?>

			</li>

			<?php
		}

		
		echo '</ul>';
		echo '</form>';
		?>
		
		<?php

		die; // terminate now that ajax is complete

	}



	function wishlist_add_action_callback() { 
		global $user_ID; 

		if ( !wp_verify_nonce( $_POST['_wpnonce'], 'add_to_wishlist' ) || !absint( $_POST['u'] ) || !absint( $_POST['p'] )) { 

			_e( 'Adding the item to your wishlist failed.', 'ignitewoo-wishlists-pro' );
			die;

		}

		if ( isset( $_POST['existing_or_new'] ) )
			$existing_or_new = absint( $_POST['existing_or_new'] );
		else
			$existing_or_new = 0;

		$user = absint( $_POST['u'] );
		$prod = absint( $_POST['p'] );
		$variation = absint( $_POST['v'] );
		$qty = absint( $_POST['q'] );

		switch ( $existing_or_new ) {

			// no wishlists defined yet, or user opted to created one
			case 0:
			case 2:

				$taxonomy_id = absint( $_POST['wishlist_num'] );

				if ( !$taxonomy_id ) { 
					_e( 'There was an error adding the wishlist. Please try again shortly.', 'ignitewoo-wishlists-pro' );
					die;
				}


				$wishlist_type = get_term( $taxonomy_id, 'c_wishlists_cat', OBJECT );

				if ( !$wishlist_type ) { 
					_e( 'There was an error adding the wishlist. Please try again shortly.', 'ignitewoo-wishlists-pro' );
					die;
				}


				if ( '' == trim( strip_tags( $_POST['wishlist_title'] ) ) ) { 
					_e( 'You must specify a Wishlist title.', 'ignitewoo-wishlists-pro' );
					die;
				}

				// We have 3 predefined types: public, private, and shared.
				// Parse to find which string is in the slug so we can define the list.
				// Do this just in case an admin modifies the taxonomies. 
				if ( strpos( $wishlist_type->slug, 'wishlist_public' ) !== false )
					    $wishlist_type = 'public';
					    
				else if ( strpos( $wishlist_type->slug, 'wishlist_private' ) !== false )
					    $wishlist_type = 'private';

				else if ( strpos( $wishlist_type->slug, 'wishlist_shared' ) !== false )
					    $wishlist_type = 'shared';

				else $wishlist_type = 'public';

				$args = array( 
					'post_type' => 'custom_wishlists',
					'post_title' => strip_tags( $_POST['wishlist_title'] ),
					'post_content' => '',
					'post_status' => 'publish', // save as draft just to be certain no public viewing can happening
					'post_author' => $user
				);

				$post_id = wp_insert_post( $args );

				if ( $post_id )

					wp_set_post_terms( $post_id, array( $taxonomy_id ), 'c_wishlists_cat' );

				else { 

					_e( 'There was an error adding the wishlist. Please try again shortly.', 'ignitewoo-wishlists-pro' );
					die;
				}


				$products[] = array( 'id' => $prod, 'vid' => $variation, 'purchased' => '', 'qty' => $qty ); // just the product ID and purchased flag

				update_post_meta( $post_id, 'wishlist_products', $products );

				update_post_meta( $post_id, 'wishlist_type', $wishlist_type );

				$event_type = isset($_POST['event-type'])?$_POST['event-type']:"";
				$event_date = isset($_POST['event-date'])?$_POST['event-date']:"";
				$co_registrant_name = isset($_POST['co-registrant-name'])?$_POST['co-registrant-name']:"";
				$co_registrant_email = isset($_POST['co-registrant-email'])?$_POST['co-registrant-email']:"";

				save_additional_wishlists_info( $post_id, $user, $event_type, $event_date, $co_registrant_name, $co_registrant_email );

				echo '<p class="wishlist_p">';  _e( "Your new wishlist was created.\n\n", 'ignitewoo-wishlists-pro' );  echo '</p>';

				echo '<p class="wishlist_p">';  _e( "The URL is :", 'ignitewoo-wishlists-pro' );  echo get_permalink( $post_id );  echo '</p>';

				die;

				break;

			// use existing wishlist
			case 1: 

				$existing_wishlist_id = isset( $_POST['existing_wishlist'] ) ? absint( $_POST['existing_wishlist'] ) : 0;

				if ( !$existing_wishlist_id || $existing_wishlist_id < 0 ) { 
					_e( 'There was an error adding the wishlist. Please try again shortly.', 'ignitewoo-wishlists-pro' );
					die;
				}

				$products = get_post_meta( $existing_wishlist_id, 'wishlist_products', true );

				$added = false;
				
				foreach( $products as $k => $p ) {

					if ( !empty( $p['qty'] ) )
						$pq = $p['qty'];
					else
						$pg = 1;
//var_dump( $variation, $prod, $p['id'], $p['vid'] );
//echo '<p>';
					if ( !empty( $variation ) && $p['id'] == $prod && $p['vid'] == $variation ) {

						$qty = $pq + $qty;
						
						$products[ $k ]['qty'] = $qty;

						$added = true;
					
					} else if ( !empty( $variation ) ) { 
					
						$added = false;
					
					} else if ( $p['id'] == $prod ) {

						$qty = $pq + $qty;

						$products[ $k ]['qty'] = $qty;

						$added = true;
					}

				}
				
				if ( !$added ) { 

						$products[] = array( 'id' => $prod, 'vid' => $variation, 'purchased' => array(), 'purchased_by' => array(), 'qty' => $qty );
				}
				
				
//var_dump( $products );
//die;
				update_post_meta( $existing_wishlist_id, 'wishlist_products', $products );

				break;

		}


		_e( 'The product was added to your wishlist!', 'ignitewoo-wishlists-pro' );
		
		die;

	}

	// insert add to wishlist button in product page
	// derived from WooCommerce core code - DO NOT insert button unless user is logged in
	function add_to_wishlist_button( $post = '', $_product = '') { 
		global $post, $user_ID, $wpdb, $product;

		if ( !$user_ID )
			$uid = $_COOKIE['wishlist_user_id'];
		else
			$uid = $user_ID;

		if ( empty( $uid ) )
			return; 

		$sql = 'select ID from ' . $wpdb->posts . ' where post_type = "custom_wishlists" and post_author = ' . $uid;

		$wishlists = $wpdb->get_results( $sql, ARRAY_A );

		$lists = array();
		
		if ( $wishlists )
		foreach( $wishlists as $w ) {

			$items = get_post_meta( $w['ID'], 'wishlist_products', true );

			if ( !$items )
				continue;

			foreach( $items as $i ) {

				if ( isset( $product->children ) && count( $product->children ) > 0 ) {

					foreach( $product->children as $c ) {

						if ( $c == $i['vid'] )
							$lists[] = $w['ID'];
					}

				} else {

					if ( $post->ID== $i['id'] )
						$lists[] = $w['ID'];
					
				}
			}
		}

		?>
		<a id="wishlist_add_hidden_link" href="#wishlist_box_wrapper" data-rel="prettyPhoto" style="display:none"></a>
		<div id="wishlist_box_wrapper" style="display:none"></div>
		<script>
		

		
		jQuery('form.variations_form')
		.on( 'check_variations', function( event, exclude, focus ) {
		
			var w_all_set = true;

			$variation_form = jQuery(this).closest('form.variations_form');
			
			$variation_form.find('.variations select').each( function() {

				if ( jQuery( this ).val().length == 0 ) {
				
					w_all_set = false;
					
				} else {
				
					w_any_set = true;
					
				}

				if ( exclude && jQuery(this).attr('name') == exclude ) {

					w_all_set = false;

				}

			});

			if ( !w_all_set ) {
				if ( ! exclude ) {
					$variation_form.parent().find('.wishlist_variation_wrap').slideUp('200');
				}
			} else {
				$variation_form.parent().find('.wishlist_variation_wrap').slideDown('200');
			}
		})
		</script>

		<?php
			$opts = get_option( 'woocommerce_ignitewoo_wishlists_settings' );

			if ( !isset( $opts['button_link_text'] ) || '' ==  trim( $opts['button_link_text'] ) )
				$text =  __( 'Add to wishlist', 'ignitewoo-wishlists-pro' );
			else
				$text = trim( $opts['button_link_text'] );

			if ( !isset( $opts['in_list_text'] ) || '' ==  trim( $opts['in_list_text'] ) )
				$intext =  __( 'Item is in one or more your wishlists:', 'ignitewoo-wishlists-pro' );
			else
				$intext = trim( $opts['in_list_text'] );
			

			if ( count( $lists ) > 0 ) {
			
				echo '<div class="in_list_text">'.$intext.'</div>';
				
				echo '<ul class="wishlists_notice_ul">';
				
				foreach( $lists as $l ) {

					echo '<li><a href="' . get_permalink( $l ) . ' ">' . get_the_title( $l ) . '</a></li>';
				}

				echo '</ul>';

			}

				
			if ( !isset( $opts['button_or_link'] ) || 'button' ==  $opts['button_or_link'] ) {

		?>
			
				<button type="button" id="wishlist_button" class="wishlist_button button alt wishlist_variation_wrap" rel="<?php echo $post->ID . ',' . $uid ?>"><?php echo $text ?></button>
		<?php

			} else if ( 'link' ==  $opts['button_or_link'] ) {

		?>

				<a href="#" id="wishlist_button" class="wishlist_button link" rel="<?php echo $post->ID . ',' . $uid ?>"><?php echo $text; ?></a>

		<?php
		
			} else {

		?>
				<a href="#" id="wishlist_button" class="wishlist_button link <?php echo $opts['button_or_link'] ?>" rel="<?php echo $post->ID . ',' . $uid ?>"><?php echo $text ?></a>
		<?php

			}
	}


	function shortcode_my_wishlists( $atts ) {

		ob_start();
		
		$this->my_wishlists();

		$ret = ob_get_clean();

		if ( '' == trim( $ret ) )
			return __( 'You have not created any wishlists yet', 'ignitewoo-wishlists-pro' );

		return $ret;

	}

	

	function my_wishlists() { 
		global $user_ID, $post;

		if ( !$user_ID && !isset( $_COOKIE['wishlist_user_id'] ) )
			return;
		else if ( $user_ID )
			$uid = $user_ID;
		else
			$uid = $_COOKIE['wishlist_user_id'];

		$wishlists = new WP_Query( 'post_type=custom_wishlists&author=' . $uid . '&orderby=id&order=asc' );

		if ( $wishlists->have_posts() ) {
		?>

			<h2 class="wishlist_myaccount_h2"><?php _e('My Wishlists', 'ignitewoo-wishlists-pro'); ?></h2>

			<ul class="your_wishlists">

		<?php
			if ( $wishlists->have_posts() ) {

				echo '<form action="" method="post">';
				
				echo '<table class="wishlist_table">';

				while ( $wishlists->have_posts() ) {

					$wishlists->the_post();

					if ( isset( $_POST['_wpnonce'] ) && isset( $_POST['update_wishlists'] ) ) {

						if ( wp_verify_nonce(  $_POST['_wpnonce'], 'update_wishlists' ) ) {
							wp_set_post_terms( $post->ID, $_POST['wishlist_type'][ $post->ID ], 'c_wishlists_cat', false );

						}

					}

					$wishlist_type = wp_get_post_terms( $post->ID, 'c_wishlists_cat', OBJECT );

					$types = get_terms( 'c_wishlists_cat', array( 'hide_empty' => false ) );

					?>

					<tr id="wishlist_<?php echo $post->ID ?>">
					
						<td><a href="<?php the_permalink() ?>"><?php the_title() ?></a></td>

						<td>
							<select name="wishlist_type[<?php echo $post->ID ?>]">

							<?php foreach( $types as $t ) { ?>

								<option value="<?php echo $t->term_id ?>" <?php selected( $t->name, $wishlist_type[0]->name, true ) ?>>
									<?php echo $t->name ?>
								</option>
							
							<?php } ?>
							
							
							</select>

							
						</td>

						<td><a class="wishlist_remove" onclick="return maybe_delete_wishlist(<?php echo $post->ID ?>)" href="#" title=" <?php _e( 'Delete wishlist', 'ignitewoo-wishlists-pro' ) ?> ">Delete</a></td>
					    
					</tr>

					<?php

				}

				echo '</table>';

				wp_nonce_field( 'update_wishlists' );

				echo '<input class="button" type="submit" name="update_wishlists" value="' . __( 'Save Wishlist Changes', 'ignitewoo-wishlists-pro' ) . '">';
				
				echo '</form>';

			}

			?>

			</ul>

			<?php 
		}

	}


	function after_cart_contents() { 

		if ( !$this->wishlist_items_in_cart ) 
			return;

		?>
			<tr>
				<td colspan="6">

					<strong>
					<?php 
						_e( 'You have', 'ignitewoo-wishlists-pro' );

						echo ' ';

						echo $this->wishlist_items_in_cart . ' ';

						if ( 1 == $this->wishlist_items_in_cart ) 
							echo __( 'item', 'ignitewoo-wishlists-pro' );
						else 
							echo __( 'items', 'ignitewoo-wishlists-pro' );

						echo ' ';

						_e( 'in your cart (indicated by an asterisk) from', 'ignitewoo-wishlists-pro' );

						echo ' ';

						if ( 1 == $this->wishlist_items_in_cart ) 
							_e( 'a wishlist.', 'ignitewoo-wishlists-pro' );
						else 
							_e( 'wishlists.', 'ignitewoo-wishlists-pro' );

						echo ' ';

						_e ( "If you don't want to buy these for those people then empty your cart and start again.", 'ignitewoo-wishlists-pro' );

						echo ' ';

						_e ( "Otherwise the related wishlists will be updated to indicate the purchase when you checkout.", 'ignitewoo-wishlists-pro' );

					?>
					</strong>

				</td>
			</tr>

		<?php

	}


	function product_title( $title = '', $values = '', $cart_item_key = ''  ) {

		if ( '' == trim( $title ) || !is_array( $values ) || '' == trim( $cart_item_key ) )
			return $title; 

		@session_start();

		$wishlist_session = $this->session_get( 'wishlist' ); // $_SESSION['wishlist'];

		$wishlist_session = maybe_unserialize( $wishlist_session );

		if ( !$wishlist_session || !is_array( $wishlist_session ) )
			return $title;

		foreach( $wishlist_session as $w ) { 

			if ( $values['product_id'] == $w['pid'] ) {

				if ( !$this->wishlist_items_in_cart ) 
					$this->wishlist_items_in_cart = 0;

				$this->wishlist_items_in_cart++;

				$title = '<strong>*</strong> ' . $title;

				break;
			}

		}
		
		return $title;

	}


	function product_receiver_detail_form() { 

		@session_start();

		$wishlist_session = $this->session_get( 'wishlist' ); //$_SESSION['wishlist'];

		$wishlist_session = maybe_unserialize( $wishlist_session );

		if ( !$wishlist_session || !is_array( $wishlist_session ) )
			return;

		echo '</div></div>';

		echo '<div class="wishlist_receiver">';

		echo '<div class="wishlist_receiver_form">';

		echo '<h3>' . __( 'Wishlist Purchase Options', 'ignitewoo-wishlists-pro' ) . '</h3>';

		echo '<table>';

		echo '<tr>';

		echo '<td><label for="gift_receiver_name">'.__('Recipient Notification?', 'ignitewoo-wishlists-pro').'</label></td>';

		echo '<td><input id="notify_receiver" type="checkbox" name="notify_receiver" value="1" />' . ' ';

		echo  __('Check this box to notify the recipient(s) of the wishlist item(s)', 'ignitewoo-wishlists-pro' );

		echo '<p><label>' . __('Note that if you do not send a message the recipient will not see who bought this item when they view their wishlist.', 'ignitewoo-wishlists-pro' ) . '</label></p>';

		echo '</td>';

		echo '</tr>';

		echo '<tr>';

		echo '<td align="top" style="vertical-align:top"><label for="notify_receiver_message">'.__('Message to Recipient', 'ignitewoo-wishlists-pro').'</label></td>';

		echo '<td>'; 

		echo '<p><label>' . __('If you are buying wishlist items for multiple people the message below will be sent to each person!', 'ignitewoo-wishlists-pro' ) . '</label></p>';

		echo '<textarea id="notify_receiver_message" name="notify_receiver_message" cols="50" rows="5"></textarea>';

		echo '</td>';

		echo '</tr>';

		echo '</table>';
	}


	function verify_wishlist_receiver_details() {
		global $woocommerce;
		
		if ( isset( $_POST['notify_receiver'] ) && '1' == $_POST['notify_receiver'] ) {

			if ( !isset( $_POST['notify_receiver_message'] ) || '' == trim( $_POST['notify_receiver_message'] ) ) 
				$woocommerce->add_error( __( 'Error: You must enter a message to the recipient for the wishlist item(s) you are purchasing - or uncheck the Recipient Notification box.', 'ignitewoo-wishlists-pro' ) );

		}
		
	}


	function add_wishlist_receiver_details_in_order( $order_id ) { 

		if ( 	isset( $_POST['notify_receiver'] ) && '1' == $_POST['notify_receiver'] && 
			isset( $_POST['notify_receiver_message'] ) && '' != trim( $_POST['notify_receiver_message'] )
		   ) { 
			
			update_post_meta( $order_id, 'notify_receiver', 1 );
			update_post_meta( $order_id, 'notify_receiver_message', trim( $_POST['notify_receiver_message'] ) );
		}

	}

	// called during the_content - checks to see if the post being displayed is a wishlist and if so output the items on the list
	function wishlist_the_content( $content = '' ) { 
		global $post, $woocommerce, $wishlist_items, $wishlist_posts, $user_ID;

		if ( 'custom_wishlists' != $post->post_type )
			return $content;

		$types = wp_get_post_terms( $post->ID, 'c_wishlists_cat' );


		// check for private wishlist
		foreach ( $types as $t )
			if ( ( 'wishlist_private' == $t->slug ) && ( $user_ID != $post->post_author ) )
				return __( 'You are not authorized to view this wishlist.', 'ignitewoo-wishlists-pro' );

		$orig_post = $post;

		if ( !$woocommerce ) 
			    $woocommerce = new woocommerce();

		$wishlist_items = get_post_meta( $post->ID, 'wishlist_products', true );

		$ids = array();
		$vids = array();

		foreach( $wishlist_items as $key => $wli ) {
			if ( !empty( $wli['vid'] ) && $wli['vid'] > 0 ) {
			    $ids[] = $wli['id'];
			    $vids[] = array( 'id' => $wli['id'], 'vid' => $wli['vid'] );
			} else
			    $ids[] = $wli['id'];
		}

		if ( !$wishlist_items ) {
			$content .=  '<p>' . __('There are no items in this wishlist.', 'ignitewoo-wishlists-pro' ) . '</p>';
			return $content;
		}

		$args = array( 
			    'post__in' => $ids, 
			    'post_type' => 'product', 
			    'order_by' => 'ID', 
			    'order' => 'ASC' 
		);

		$wishlist_posts = new WP_Query( $args ); 

		if ( count( $vids ) > 0 )
			foreach( $vids as $v ) {

				    $p = get_post( $v['id'] );

				    $p->variation_id = $v['vid'];

				    $wishlist_posts->posts[] = $p;

				    $wishlist_posts->post_count++;

			}

		add_filter('single_product_large_thumbnail_size', array( &$this, 'wishlist_thumbnail' ), 9999999, 3 );

		ob_start();

		$this->wishlist_get_template( 'single-wishlist-product.php' );

		$content = str_replace( array( "\n", "\r", "\t" ), '', ob_get_contents() );

		ob_end_clean();

		$post = $orig_post;

		return $content;

	}


	function wishlist_scripts() { 

		global $post, $user_ID;

	?>
		<script>
			    function maybe_remove_wishlist_item( id, vid, wlid ) { 

				if ( confirm('Are you sure you want to remove this item?') ) {
 
					<?php echo 'nonce = "' . wp_create_nonce  ( 'wishlist_nonce' ) . '";' ?>

					jQuery.ajax({
						type	: "POST",
						cache	: false,
						url	: "<?php echo admin_url( 'admin-ajax.php' ) ?>",
						data	: { nonce: nonce, user: <?php echo $user_ID ?>, prod: id, vid: vid, wlid: wlid , action: 'wishlist_remove_item'  },
						success: function( data ) {
								if ( data.length > 0 && 'ok' == data ) {
									var tag = id;
									if ( null != vid && vid.length > 0 )
										tag = tag + '_' + vid;
									else
										tag = tag + '_0';
										
									jQuery('#wishlist_product_' + tag).fadeOut(
										500,
											function() {
												jQuery('#wishlist_product_' + tag).remove();
											}
									);
								}
							}
					});

				}

				return false;

			    }

			    function maybe_delete_wishlist( id ) { 

				if ( confirm('Are you sure you want to delete this wishlist?') ) {
 
					<?php echo 'nonce = "' . wp_create_nonce  ( 'wishlist_nonce' ) . '";' ?>

					jQuery.ajax({
						type	: "POST",
						cache	: false,
						url	: "<?php echo admin_url( 'admin-ajax.php' ) ?>",
						data	: { nonce: nonce, user: <?php echo $user_ID ?>, wlid: id , action: 'wishlist_delete'  },
						success: function( data ) {
								jQuery('#wishlist_' + id).fadeOut( 500, function() { jQuery('#wishlist_' + id).remove(); });
							}
					});

				}

				return false;

			    }

			    function maybe_buy_item( id, vid, wlid, u, ul ) { 

				if ( confirm('Are you sure you want to buy this item for ' + ul + '?' ) ) {
 
					<?php echo 'nonce = "' . wp_create_nonce  ( 'wishlist_nonce' ) . '";' ?>

					jQuery.ajax({
						type	: "POST",
						dataType : 'json',
						cache	: false,
						url	: "<?php echo admin_url( 'admin-ajax.php' ) ?>",
						data	: { nonce: nonce, user: u, wlid: wlid , prod_id: id, var_id: vid, action: 'wishlist_buy_item'  },
						success: function( data ) { 
							if ( null != data['url'] )
								window.location = data['url'];
 							else
								alert( data['error'] );
						}
					});

				}

				return false;

			    }

		</script>

	<?php
	}


	function wishlist_remove_item_callback() { 

		if ( !isset( $_POST['nonce'] ) )
			die;

		if ( !wp_verify_nonce( $_POST['nonce'], 'wishlist_nonce' ) ) 
			die;

		$wlid = absint( $_POST['wlid'] );

		$prod_id = absint( $_POST['prod'] );

		$var_id = absint( $_POST['vid'] );

		if ( $wlid <= 0 || $prod_id <= 0)
			die;

		$wishlist_items = get_post_meta( $wlid, 'wishlist_products', true );

		$unsets = array();

		foreach( $wishlist_items as $key => $val ) { 

			if ( $var_id ) { 

			    if ( $val['id'] == $prod_id && $val['vid'] == $var_id ) 
					$unsets[] = $key; 


			} else { 

			    if ( $val['id'] == $prod_id ) 
					$unsets[] = $key; 

			}

		}

		foreach( $unsets as $u ) 
			unset( $wishlist_items[ $u ] );

		// renumber array to ensure zero offset
		$wishlist_items = array_values( $wishlist_items );

		update_post_meta( $wlid, 'wishlist_products', $wishlist_items ); 

		die( 'ok' );

	}


	function wishlist_delete_callback() { 

		if ( !isset( $_POST['nonce'] ) )
			die;

		if ( !wp_verify_nonce( $_POST['nonce'], 'wishlist_nonce' ) ) 
			die;

		$wlid = absint( $_POST['wlid'] );

		if ( $wlid <= 0 )
			die;

		wp_delete_post( $wlid, true ); // true = bypass trash, delete outright

		die;

	}


	function wishlist_buy_item_callback() { 
                global $woocommerce, $wpdb;

		if ( !isset( $_POST['nonce'] ) )
			die;

		if ( !wp_verify_nonce( $_POST['nonce'], 'wishlist_nonce' ) )
			die( '-1' );

		$wlid = absint( $_POST['wlid'] );
		$user = absint( $_POST['user'] );
		$prod_id = absint( $_POST['prod_id'] );
		$var_id = absint( $_POST['var_id'] );

		if ( $wlid <= 0 || $user <=0 || $prod_id <= 0 )
			die;

		$variation = array();

		if ( $var_id > 0 ) { 

			$sql = 'select * from ' . $wpdb->postmeta . ' where post_id = ' . $var_id . ' and meta_key like "attribute_%"'; 

			$vals = $wpdb->get_results( $sql );

			if ( $vals )
			foreach( $vals as $v ) { 

				$type = ucfirst( str_replace( 'attribute_pa_', '', $v->meta_key ) );

				$variation[ $type ] = $v->meta_value;

			}

		}

        
                if ( $woocommerce->cart->add_to_cart( $prod_id, 1, $var_id, $variation ) ) {

			$added = true;

                } else {

			$out = array( 'error' => $woocommerce->errors[0] );

			$added = false;
                }
                
		@session_start(); // in case it's not started yet

		$wishlist_session = $this->session_get( 'wishlist' ); //$_SESSION['wishlist'];

		$wishlist_session = maybe_unserialize( $wishlist_session );

		// avoid adding duplicates to the session
		$in_there = false; 

		if ( is_array( $wishlist_session ) ) 
		foreach ( $wishlist_session as $key => $vals ) { 

			if ( $var_id > 0 ) { 
				if ( $vals['pid'] == $prod_id && $vals['wlid'] == $wlid && $vals['vid'] == $var_id ) 
					$in_there = true;
			} else { 
				if ( $vals['pid'] == $prod_id && $vals['wlid'] == $wlid ) 
					$in_there = true;
			}
		}

		if ( !$in_there ) 
			$wishlist_session[] = array( 'pid' => $prod_id, 'vid' => $var_id, 'wlid' => $wlid, 'user' => $user );

		$this->session_set( 'wishlist', serialize( $wishlist_session ) );
		//$_SESSION['wishlist'] = serialize( $wishlist_session );

		$url = get_permalink( get_option( 'woocommerce_cart_page_id', false ) );

		if ( $added ) 
			$out = array( 'url' => $url );

		echo json_encode( $out );

		die;

	}


	function wishlist_thumbnail( $test ) { 

		return array(50,50);

	}


	function wishlist_new_order( $order_id, $order ) {

		if ( version_compare( WOOCOMMERCE_VERSION, '2.0.0' ) >= 0 )
			$this->wishlist_new_order_2x( $order_id, $order );
		else
			$this->wishlist_new_order_1x( $order_id, $order );
	
	}

	
	function wishlist_new_order_2x( $order_id, $order ) {

		$wlsession = $this->session_get( 'wishlist' ); // $_SESSION['wishlist'];

		if ( !$wlsession ) return;

		$wlsession = maybe_unserialize( $wlsession );

		if ( !is_array( $wlsession ) ) return;

		$order = new WC_Order( $order_id );

		$order_items = $order->get_items();

		if ( sizeof( $order_items ) <= 0 )
			return;

		$unsets = array();

		$p = array();

		foreach( $wlsession as $key => $val )  {

			foreach ( $order_items as $item ) {

				$pid = '';

				$vid = '';

				$qty = '';

				// find product ID and variation ID
				foreach( $item['item_meta'] as $kk => $vv ) {

					if ( '_product_id' == $kk )
						$pid = $vv[0];
						
					if ( '_variation_id' == $kk )
						$vid = $vv[0];

					if ( '_qty' == $kk )
						$qty = $vv[0];
				}

				if ( !isset( $pid ) || absint( $pid ) <= 0 )
					continue;

				$gotit = false;

				if ( count( $p ) > 0 )
				foreach( $p as $z ) {

					if ( !empty( $vid ) && in_array( $vid, array_values( $z ) ) ) {

						$gotit = true;

						break;

					} else if ( empty( $vid ) && in_array( $pid, array_values( $z ) ) ) {

						$gotit = true;

						break;
					}

				}

				if ( $gotit ) {
					$gotit = false;
					continue;
				}

				// variation product?
				if ( !empty( $vid ) && $val['vid'] == $vid ) {
					$p[] = array( 'wlid' => $val['wlid'], 'item_id' => $pid, 'pid' => $pid, 'vid' => $val['vid'], 'user' => $val['user'], 'qty_purchased' => $qty );

				} else if ( empty( $vid ) ) {

					$p[] = array( 'wlid' => $val['wlid'], 'item_id' => $pid, 'pid' => $pid, 'vid' => '', 'user' => $val['user'], 'qty_purchased' => $qty  );

				}

			}


		}

		if ( count( $p ) > 0 )
			update_post_meta( $order->id, 'wishlist_purchased_for', $p );

		$this->session_set( 'wishlist', '' );
		//$_SESSION['wishlist'] = '';

	}

	
	function wishlist_new_order_1x( $order_id, $order ) {

		$wlsession = $this->session_get( 'wishlist' ); // $_SESSION['wishlist'];

		if ( !$wlsession ) return;

		$wlsession = maybe_unserialize( $wlsession );

		if ( !is_array( $wlsession ) ) return;

		$order = new WC_Order( $order_id );

		$order_items = $order->get_items();

		if ( sizeof( $order_items ) <= 0 ) 
			return;

		$unsets = array();

		$p = array();

		foreach( $wlsession as $key => $val )  {

			foreach ( $order_items as $item ) {

				if ( !isset( $item['id'] ) || absint( $item['id'] ) <= 0 ) 
					continue; 

				$gotit = false; 

				foreach( $p as $z ) {

					if ( !empty( $item['variation_id'] ) && in_array( $item['variation_id'], array_values( $z ) ) ) { 

						$gotit = true;

						break;

					} else if ( empty( $item['variation_id'] ) && in_array( $item['id'], array_values( $z ) ) ) { 

						$gotit = true;

						break;
					}

				}

				if ( $gotit ) {
					$gotit = false; 
					continue;
				}

				// variation product? 
				if ( !empty( $item['variation_id'] ) && $val['vid'] == $item['variation_id'] ) { 
					$p[] = array( 'wlid' => $val['wlid'], 'item_id' => $item['id'], 'pid' => $item['id'], 'vid' => $val['vid'], 'user' => $val['user'], 'qty_purchased' => $item['qty'] );

				} else if ( empty( $item['variation_id'] ) ) {  

					$p[] = array( 'wlid' => $val['wlid'], 'item_id' => $item['id'], 'pid' => $item['id'], 'vid' => '', 'user' => $val['user'], 'qty_purchased' => $item['qty']  );

				}

			}


		}

		if ( count( $p ) > 0 ) 
			update_post_meta( $order->id, 'wishlist_purchased_for', $p );

		$this->session_set( 'wishlist', '' );
		//$_SESSION['wishlist'] = '';

	}


	function wishlist_completed_purchase( $order_id ) { 

		$wishlist_for = get_post_meta( $order_id, 'wishlist_purchased_for', true );

		if ( !$wishlist_for || !is_array( $wishlist_for ) ) 
			return;

		$order = new WC_Order( $order_id );

		foreach ( $wishlist_for as $key => $list ) { 

			if ( !isset( $list['wlid'] ) || absint( $list['wlid'] ) <= 0 )
				continue; 

			$wishlist_items = get_post_meta( $list['wlid'], 'wishlist_products', true );

			if ( !$wishlist_items || !is_array( $wishlist_items ) ) 
				continue; 

			foreach( $wishlist_items as $wkey => $witem ) { 

				if ( $witem['vid'] == $list['vid'] && $witem['id'] == $list['pid'] ) { 

					$wishlist_items[ $wkey ]['purchased'] = 1;

					if ( !isset( $wishlist_items[ $wkey ]['purchased_by'] ) || empty( $wishlist_items[ $wkey ]['purchased_by'] ) )
						$wishlist_items[ $wkey ]['purchased_by'] = array();

					$user_qty_updated = false;
						
					if ( count( $wishlist_items[ $wkey ]['purchased_by'] ) > 0 )
					foreach( $wishlist_items[ $wkey ]['purchased_by'] as $k => $v ) {

						if ( $v['user'] == $order->user_id ) {

							$q = absint( $wishlist_items[ $wkey ]['purchased_by'][$k]['qty'] );
							$wishlist_items[ $wkey ]['purchased_by'][$k]['qty'] = $wishlist_items[ $wkey ]['purchased_by'][$k]['qty'] + $list['qty_purchased'];

							$user_qty_updated = true;
							
						} 
					
					}

					if ( !$user_qty_updated ) { 
					
						$wishlist_items[ $wkey ]['purchased_by'][] = array( 'user' => $order->user_id, 'qty' => $list['qty_purchased'] );

					}

					if ( get_option( $order->id, 'notify_receiver', false ) )
						$wishlist_items[ $wkey ]['show_purchased_by_name'] = '1';
					else
						$wishlist_items[ $wkey ]['show_purchased_by_name'] = '';

				} else if ( $witem['id'] == $list['pid'] ) { 

					$wishlist_items[ $wkey ]['purchased'] = 1;

					if ( !isset( $wishlist_items[ $wkey ]['purchased_by'] ) || empty( $wishlist_items[ $wkey ]['purchased_by'] ) )
						$wishlist_items[ $wkey ]['purchased_by'] = array();

					$user_qty_updated = false;

					if ( count( $wishlist_items[ $wkey ]['purchased_by'] ) > 0 )
					foreach( $wishlist_items[ $wkey ]['purchased_by'] as $k => $v ) {

						if ( $v['user'] == $order->user_id ) {

							$q = absint( $wishlist_items[ $wkey ]['purchased_by'][$k]['qty'] );
							$wishlist_items[ $wkey ]['purchased_by'][$k]['qty'] = $wishlist_items[ $wkey ]['purchased_by'][$k]['qty'] + $list['qty_purchased'];

							$user_qty_updated = true;

						}

					}

					if ( !$user_qty_updated ) {

						$wishlist_items[ $wkey ]['purchased_by'][] = array( 'user' => $order->user_id, 'qty' => $list['qty_purchased'] );

					}


					if ( get_option( $order->id, 'notify_receiver', false ) )
						$wishlist_items[ $wkey ]['show_purchased_by_name'] = '1';
					else
						$wishlist_items[ $wkey ]['show_purchased_by_name'] = '';

				}

			}

			update_post_meta( $list['wlid'], 'wishlist_products', $wishlist_items );

		}

	}


	function wishlist_cancel_purchase( $order_id ) { 

		$wishlist_for = get_post_meta( $order_id, 'wishlist_purchased_for', true );

		if ( !$wishlist_for || !is_array( $wishlist_for ) ) 
			return;

		$order = new WC_Order( $order_id );

		foreach ( $wishlist_for as $key => $list ) { 

			if ( !isset( $list['wlid'] ) || absint( $list['wlid'] ) <= 0 )
				continue; 

			$wishlist_items = get_post_meta( $list['wlid'], 'wishlist_products', true );

			if ( !$wishlist_items || !is_array( $wishlist_items ) ) 
				continue; 

			foreach( $wishlist_items as $wkey => $witem ) { 

				if ( $witem['vid'] == $list['vid'] && $witem['id'] == $list['pid'] ) {

					if ( !isset( $wishlist_items[ $wkey ]['purchased_by'] ) || empty( $wishlist_items[ $wkey ]['purchased_by'] ) )
						$wishlist_items[ $wkey ]['purchased_by'] = array();

					$user_qty_updated = false;

					if ( count( $wishlist_items[ $wkey ]['purchased_by'] ) > 0 )
					foreach( $wishlist_items[ $wkey ]['purchased_by'] as $k => $v ) {

						if ( $v['user'] == $order->user_id ) {

							$q = absint( $wishlist_items[ $wkey ]['purchased_by'][$k]['qty'] );

							$q = $q - $list['qty_purchased'];

							if ( $q > 0 ) 
								$wishlist_items[ $wkey ]['purchased_by'][$k]['qty'] = $wishlist_items[ $wkey ]['purchased_by'][$k]['qty'] - $list['qty_purchased'];
							else
								unset( $wishlist_items[ $wkey ]['purchased_by'][ $k ] );

							$user_qty_updated = true;

						}

					}

					if ( !$user_qty_updated ) {

						$wishlist_items[ $wkey ]['purchased_by'][] = array( 'user' => $order->user_id, 'qty' => 0 );

					}

					$wishlist_items[ $wkey ]['purchased'] = '';

					unset( $wishlist_items[ $wkey ]['show_purchased_by_name'] );

				} else if ( $witem['id'] == $list['pid'] ) { 


					if ( !isset( $wishlist_items[ $wkey ]['purchased_by'] ) || empty( $wishlist_items[ $wkey ]['purchased_by'] ) )
						$wishlist_items[ $wkey ]['purchased_by'] = array();

					$user_qty_updated = false;

					if ( count( $wishlist_items[ $wkey ]['purchased_by'] ) > 0 )
					foreach( $wishlist_items[ $wkey ]['purchased_by'] as $k => $v ) {

						if ( $v['user'] == $order->user_id ) {

							$q = absint( $wishlist_items[ $wkey ]['purchased_by'][$k]['qty'] );

							if ( $q > 0 )
								$wishlist_items[ $wkey ]['purchased_by'][$k]['qty'] = $wishlist_items[ $wkey ]['purchased_by'][$k]['qty'] - $list['qty_purchased'];
							else
								unset( $wishlist_items[ $wkey ]['purchased_by'][ $k ] );

							$user_qty_updated = true;

						}

					}

					if ( !$user_qty_updated ) {

						$wishlist_items[ $wkey ]['purchased_by'][] = array( 'user' => $order->user_id, 'qty' => 0 );

					}
					
					$wishlist_items[ $wkey ]['purchased'] = '';

					unset( $wishlist_items[ $wkey ]['show_purchased_by_name'] );

				}

			}

			update_post_meta( $list['wlid'], 'wishlist_products', $wishlist_items );

		}

	}

	
	// Display owner info - only works for non-guest wishlists because there is no user info
	function wishlist_item_meta( $item_id = '', $item = '', $_product = '' ) {
		global $thepostid, $theorder;
		
		if ( empty( $theorder ) )
			return;

		$wish_meta = get_post_meta( $theorder->id, 'wishlist_purchased_for', true );
		
		if ( empty( $wish_meta ) || !is_array( $wish_meta ) )
			return;
		
		$pid = $item['product_id'];
		
		$vid = isset( $item['variation_id'] ) ? $item['variation_id'] : '';

		foreach( $wish_meta as $meta ) { 
		
			if ( empty( $meta['user'] ) )
				continue;
		
			if ( $meta['pid'] != $pid )
				continue;

			if ( !empty( $vid ) && absint( $vid ) !== absint( $meta['vid'] ) )
				continue;
			
			if ( empty( $meta['user'] ) )
				continue;
				
			$uid = absint( $meta['user'] );
			
			break;
		}
		
		$user_meta = get_user_meta( $uid );

		if ( !$user_meta )
			return;
			
		$fields = $this->wishlist_owner_fields_for_order();
			
		?>
		<style>
		table.wishlist_owner_info tr td { padding-left: 7px !important; }
		</style>
		<table class="meta wishlist_owner_info" cellspacing="0">
			<thead>
				<tr>
					<th colspan="1" style="vertical-align:middle;padding:3px 0 3px 5px"><?php _e( 'List Owner Billing', 'ignitewoo-wishlists-pro' ) ?></th>
					<th colspan="1" style="vertical-align:middle;padding:3px 0 3px 5px"><?php _e( 'List Owner Shipping', 'ignitewoo-wishlists-pro' ) ?></th>
				</tr>
			</thead>
			<tbody class="meta_items">
				<tr>
					<td>
						<table>
						<?php foreach( $fields as $name => $text ) { ?>
						
							<?php if ( !empty( $user_meta['billing_' . $name ][0] ) ) { ?>
							<tr>
								<td style="text-align:left"><?php echo $user_meta['billing_' . $name ][0] ?></td>
							</tr>
							<?php } ?>
						<?php } ?>
						</table>
					</td>
					<td>
						<table>
						<?php foreach( $fields as $name => $text ) { ?>
							
							<?php if ( !empty( $user_meta['shipping_' . $name ][0] ) ) { ?>
							<tr>
								<td style="text-align:left"><?php echo $user_meta['shipping_' . $name ][0] ?></td>
							</tr>
							<?php } ?>
						<?php } ?>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<?php

	}
	
	
	function wishlist_owner_fields_for_order() { 
		$fields = array(
			'first_name' => array(
				'label' => __( 'First Name', 'ignitewoo-wishlists-pro' ),
			),
			'last_name' => array(
				'label' => __( 'Last Name', 'ignitewoo-wishlists-pro' ),
			),
			'company' => array(
				'label' => __( 'Company', 'ignitewoo-wishlists-pro' ),
			),
			'address_1' => array(
				'label' => __( 'Address 1', 'ignitewoo-wishlists-pro' ),
			),
			'address_2' => array(
				'label' => __( 'Address 2', 'ignitewoo-wishlists-pro' ),
			),
			'city' => array(
				'label' => __( 'City', 'ignitewoo-wishlists-pro' ),
			),
			'postcode' => array(
				'label' => __( 'Postcode', 'ignitewoo-wishlists-pro' ),
			),
			'country' => array(
				'label'   => __( 'Country', 'ignitewoo-wishlists-pro' ),
			),
			'state' => array(
				'label' => __( 'State/County', 'ignitewoo-wishlists-pro' ),
			),
			'email' => array(
				'label' => __( 'Email', 'ignitewoo-wishlists-pro' ),
			),
			'phone' => array(
				'label' => __( 'Phone', 'ignitewoo-wishlists-pro' ),
			),
		);
		
		return $fields;
	}
	
	// send an email to the purchaser of a gift cert to notify them of the gift cert code
	function wishlist_completed_order_customer_notification( $order_id ) {
		global $woocommerce, $wpdb;

		$send_msg = get_post_meta( $order_id, 'notify_receiver', true );

		if ( !$send_msg || '' == $send_msg || '1' != $send_msg ) 
			return;

		$send_msg = get_post_meta( $order_id, 'notify_receiver_already', true );

		if ( $send_msg ||  '1' == $send_msg ) 
			return;

		$message = get_post_meta( $order_id, 'notify_receiver_message', true );

		if ( !$message || '' == trim( $message ) ) 
			return;

		$message = wpautop( $message );

		$witems = get_post_meta( $order_id, 'wishlist_purchased_for', true ); 

		if ( !$witems || !is_array( $witems ) ) 
			return;

		if ( !$woocommerce->woocommerce_email ) 
			$woocommerce->mailer();

		foreach( $witems as $key => $item ) { 

			if ( !$item['user'] || !$item['wlid'] || !$item['pid'] )
				continue;

			$userdata = get_userdata( $item['user'] );

			if ( !$userdata || is_wp_error( $userdata ) || ( !isset( $userdata->data->user_email ) && !is_email( $userdata->data->user_email ) ) )
				continue;

			$to = $userdata->data->user_email;

			global $followup_content, $email_heading, $wishlist_item;

			$wishlist_item = get_the_title( $item['pid'] );

			if ( '' != $item['vid'] ) { 

				$type = '';
				$value = '';

				$sql = 'select * from ' . $wpdb->postmeta . ' where post_id = ' . $item['vid'] . ' and meta_key like "attribute_%"'; 

				$vals = $wpdb->get_results( $sql );
				if ( $vals ) 
				foreach( $vals as $v ) { 

					$type = ucfirst( str_replace( 'attribute_', '', $v->meta_key ) );

					$value = $v->meta_value;

					break;
				}

				$wishlist_item .= ' &ndash; ' . $type . ': ' . $value;

			}

			$followup_content = $message; 

			$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );

			$subject = __( 'Wishlist Item Purchased!', 'ignitewoo-wishlists-pro' );

			$subject = sprintf( __( '[%s] %s', 'ignitewoo-wishlists-pro' ), $blogname, $subject );

			$email_heading = $subject;

			ob_start();

			$template_name = 'emails/wishlist_email_template.php';
			
			$template_path = '';

			$default_path = dirname( __FILE__ ) . '/';
			
			$located = woocommerce_locate_template( $template_name, $template_path, $default_path );

			if ( $located ) { 

				do_action( 'woocommerce_before_template_part', $template_name, '', $located );

				require( $located );

				do_action( 'woocommerce_after_template_part', $template_name, '', $located );

			} else {

				ob_end_clean();

				return;

			}

			$msg = ob_get_clean();

			// Queue additional mail envelope headers
			$headers = apply_filters('woocommerce_email_headers', '', 'followup_message');
			
			$mailer = $woocommerce->mailer();
			
			$message = $mailer->wrap_message( $subject, $msg );

			$mailer->send( $to, $subject, $msg, $headers );
			
			//$woocommerce->woocommerce_email->send( $to, $subject, $msg, $headers );

			update_post_meta( $order_id, 'notify_receiver_already', '1' );

		}

	}


	// based on similar function in woocommerce
	// locate and load the gift cert email template
	function wishlist_get_template( $template_name ) { 
		global $woocommerce;
		
		if ( defined( 'WC_TEMPLATE_PATH' ) )
			$url = WC_TEMPLATE_PATH;
		else 
			$url = $woocommerce->template_url;
			

		if ( file_exists( get_stylesheet_directory() . '/' . $url . 'wishlists/' . $template_name ) ) 

			load_template( get_stylesheet_directory() . '/' . $url . 'wishlists/' . $template_name, false );

		elseif ( file_exists( get_stylesheet_directory() . '/wishlists/' . $template_name )) 

			load_template( get_stylesheet_directory() . '/wishlists/' . $template_name , false );

		else 

			load_template( dirname(__FILE__) . '/wishlists/' . $template_name , false );

	}


	// based on similar function in woocommerce
	// locate and load the gift cert email template
	function wishlist_get_styles() { 
		global $woocommerce, $post;
		
		if ( !isset( $post ) || !is_object( $post ) )
			return;
			
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		
		if ( isset( $post ) && 'custom_wishlists' == $post->post_type || 'product' == $post->post_type ) {

			wp_enqueue_script( 'prettyPhoto', WC()->plugin_url() . '/assets/js/prettyPhoto/jquery.prettyPhoto.js', array( 'jquery' ), WC()->version, true );
			
			wp_enqueue_style( 'woocommerce_prettyPhoto_css', WC()->plugin_url() . '/assets/css/prettyPhoto.css' );

		}

		if ( defined( 'WC_TEMPLATE_PATH' ) )
			$url = WC_TEMPLATE_PATH;
		else 
			$url = $woocommerce->template_url;

		if ( file_exists( get_stylesheet_directory() . '/' . $url . '/wishlist.css' ) ) {

			$surl = get_bloginfo('stylesheet_directory') . '/' . $url . '/wishlist.css'; 
			wp_register_style('wishlist_styles', $surl );
			wp_enqueue_style( 'wishlist_styles');


		} elseif ( file_exists( get_stylesheet_directory() . '/wishlist.css' )) {

			$surl = get_bloginfo('stylesheet_directory') . '/wishlist.css'; 
			wp_register_style('wishlist_styles', $surl );
			wp_enqueue_style( 'wishlist_styles');

		} else {

			$surl = plugins_url('/wishlists/wishlist.css', __FILE__ ); 
			wp_register_style('wishlist_styles', $surl );
			wp_enqueue_style( 'wishlist_styles');

		}

	}


	function filter_wishlists( $arr ) { 

		if ( !is_search() ) return $arr;

		if ( !$arr ) return $arr;

		$unsets = array(); 

		foreach( $arr as $key => $post ) { 

			if ( 'custom_wishlists' == $post->post_type ) { 

				$terms = wp_get_post_terms( $post->ID, 'c_wishlists_cat' );

				if ( !$terms ) 
					continue; 

				foreach( $terms as $t ) { 

					if ( 'wishlist_shared' == $t->slug || 'wishlist_private' == $t->slug ) 
						$unsets[] = $key;
				} 

			}

		}

		foreach( $unsets as $u ) 
			unset( $arr[ $u ] );

		// renumber array so that it has a zero offset
		$arr = array_values( $arr );

		return $arr;

	}


	/** ==================== WISHLISTPOST TYPE FUNCTIONS ======================= */

	// based on similar code in WooCommerce core code
	function register_wishlist_post_type() { 

		if ( current_user_can( 'manage_woocommerce' ) ) 
			$show_in_menu = __( 'woocommerce', 'ignitewoo-wishlists-pro' );
		else 
			$show_in_menu = false;

		register_post_type( "custom_wishlists",
			    array(
				    'labels' => array(
						    'name'                          => __( 'Wishlists', 'ignitewoo-wishlists-pro' ),
						    'singular_name'                 => __( 'Wishlist', 'ignitewoo-wishlists-pro' ),
						    'add_new'                       => __( 'Add Wishlist', 'ignitewoo-wishlists-pro' ),
						    'add_new_item'                  => __( 'Add New Wishlist', 'ignitewoo-wishlists-pro' ),
						    'edit'                          => __( 'Edit', 'ignitewoo-wishlists-pro' ),
						    'edit_item'                     => __( 'Edit Wishlist', 'ignitewoo-wishlists-pro' ),
						    'new_item'                      => __( 'New Wishlist', 'ignitewoo-wishlists-pro' ),
						    'view'                          => __( 'View Wishlists', 'ignitewoo-wishlists-pro' ),
						    'view_item'                     => __( 'View Wishlist', 'ignitewoo-wishlists-pro' ),
						    'search_items'                  => __( 'Search Wishlists', 'ignitewoo-wishlists-pro' ),
						    'not_found'                     => __( 'No Wishlists found', 'ignitewoo-wishlists-pro' ),
						    'not_found_in_trash'            => __( 'No Wishlists found in trash', 'ignitewoo-wishlists-pro' ),
						    'parent'                        => __( 'Parent Wishlist', 'ignitewoo-wishlists-pro' )
					    ),
				    'description'                   => __( 'This is where you can add new wishlists if you want to.', 'ignitewoo-wishlists-pro' ),
				    'public'                        => true,
				    'show_ui'                       => true,
				    'capability_type'               => 'post',
				    'capabilities' => array(
					    'publish_posts'         => 'manage_woocommerce',
					    'edit_posts'            => 'manage_woocommerce',
					    'edit_others_posts'     => 'manage_woocommerce',
					    'delete_posts'          => 'manage_woocommerce',
					    'delete_others_posts'   => 'manage_woocommerce',
					    'read_private_posts'    => 'manage_woocommerce',
					    'edit_post'             => 'manage_woocommerce',
					    'delete_post'           => 'manage_woocommerce',
					    'read_post'             => 'manage_woocommerce',
				    ),
				    'publicly_queryable'    => true,
				    'exclude_from_search'   => false,
				    'show_in_menu' 		=> $show_in_menu,
				    'hierarchical'          => true,
				    'rewrite'               => array( 'slug' => __( 'wishlist', 'ignitewoo-wishlists-pro' ), 'with_front' => false, 'feeds' => false ),
				    'query_var'             => true,
				    'supports'              => array( 'title', 'editor', 'custom-fields', 'comments' ),
				    'show_in_nav_menus'     => false,
				    'taxonomies'	    => array( 'c_wishlists_cat' )
			    )
		    );

		    // Wishlist taxonomy has no admin interface - taxonomies are predefined
		    register_taxonomy( 'c_wishlists_cat',
		    array('custom_wishlists'),
		    array(
			'hierarchical'			=> true,
			'update_count_callback'		=> '_update_post_term_count',
			'label'				=> __( 'Wishlist Categories', 'ignitewoo-wishlists-pro'),
			'labels' => array(
				'name'			=> __( 'Wishlist Categories', 'ignitewoo-wishlists-pro'),
				'singular_name'		=> __( 'Wishlist Category', 'ignitewoo-wishlists-pro'),
				'search_items'		=> __( 'Search Wishlist Categories', 'ignitewoo-wishlists-pro'),
				'all_items'		=> __( 'All Wishlist Categories', 'ignitewoo-wishlists-pro'),
				'parent_item'		=> __( 'Parent Wishlist Category', 'ignitewoo-wishlists-pro'),
				'parent_item_colon' 	=> __( 'Parent Wishlist Category:', 'ignitewoo-wishlists-pro'),
				'edit_item'		=> __( 'Edit Wishlist Category', 'ignitewoo-wishlists-pro'),
				'update_item'		=> __( 'Update Wishlist Category', 'ignitewoo-wishlists-pro'),
				'add_new_item'		=> __( 'Add New Wishlist Category', 'ignitewoo-wishlists-pro'),
				'new_item_name'		=> __( 'New Wishlist Category Name', 'ignitewoo-wishlists-pro')
			    ),
			'show_in_nav_menus'		=> false,
			'show_tagcloud'			=> false,
			'public'			=> true,
			'show_ui'			=> true,
			'query_var'			=> true,
			'rewrite'			=> '', // array( 'slug' => $category_base . $category_slug, 'with_front' => false ),
			'show_in_menu'          => $show_in_menu,
		    )
		);

		// add the default taxonomies - based on the way Amazon creates wishlists
		$term = term_exists( 'Public', 'c_wishlists_cat' ); // array is returned if taxonomy is given

		if ( !$term ) 
			wp_insert_term(
				'Public', // the term 
				'c_wishlists_cat', // the taxonomy
				array(
				    'description'=> __( 'Anyone can search and/or view this wishlist', 'ignitewoo-wishlists-pro' ),
				    'slug' => __( 'wishlist_public', 'ignitewoo-wishlists-pro' ),
				    'parent'=> ''
				)
			);

		$term = term_exists( 'Private', 'c_wishlists_cat' ); // array is returned if taxonomy is given

		if ( !$term ) 
			wp_insert_term(
				'Private', // the term 
				'c_wishlists_cat', // the taxonomy
				array(
				    'description'=> 'Only you can view this wishlist',
				    'slug' => __( 'wishlist_private', 'ignitewoo-wishlists-pro'),
				    'parent'=> ''
				)
			);

		$term = term_exists( 'Shared', 'c_wishlists_cat' ); // array is returned if taxonomy is given

		if ( !$term ) 
			wp_insert_term(
				'Shared', // the term 
				'c_wishlists_cat', // the taxonomy
				array(
				    'description'=> 'Only accessible by people who have the URL.',
				    'slug' => __( 'wishlist_shared', 'ignitewoo-wishlists-pro' ),
				    'parent'=> ''
				)
			);

	}


	function wishlist_link_filter( $post_link, $id = 0, $leavename = FALSE ) {

		if ( strpos( $post_link, '%author%' ) === false )
			return $post_link;

		if ( is_object( $id ) )
			$id = $id->ID;

		$post = get_post($id);

		if( !is_object( $post ) || $post->post_type != 'custom_wishlists' )
			return $post_link;

		$nicename = get_userdata( $post->post_author );
		$user_name = $nicename->user_login;
		$nicename = $nicename->user_nicename;

		if ( empty( $nicename ) )
			$nicename = $user_name;

		return str_replace( '%author%', $nicename, $post_link );
  	}



	// add columns to the Gift Cert list view in the admin panel
	function add_wishlist_columns( $columns ) { 

		// get these to reorder the column display: 
		$temp = array_shift( $columns ); // get first item, which checkbox field
		
		$temp2 = array_pop( $columns ); // get last item, which is date

		$new_columns['cb'] = $temp;

		$new_columns['id'] = __( 'ID', 'ignitewoo-wishlists-pro' );

		$new_columns = array_merge( $new_columns, $columns );

		$new_columns['items'] = __( 'Items', 'ignitewoo-wishlists-pro' );

		$new_columns['date'] = $temp2;

		return $new_columns;

	}


	// Make the additional columns sortable
	function sortable_columns( $columns ) {

		$new_columns = array(
		    'id' => 'id',
		    'items' => 'items',
		);

		return array_merge( $columns, $new_columns );
	}
 

	// populat column data for each gift cert
	function manage_wishlist_columns( $column_name, $id ) {
		global $wpdb;

		switch ($column_name) {
			case 'id':
				echo $id;
				break;

			case 'items': 
				$items = get_post_meta( $id, 'wishlist_products', true );
				echo count ( $items );
				break;

			default:
				break;
		}
	}


	// display a dropdown box for filtering the wishlists
	function restrict_by_wishlist() {
		global $typenow;

		if ( 'custom_wishlists' != $typenow ) return;

		$post_types = get_post_types( array( '_builtin' => false ) );

		if ( in_array( $typenow, $post_types ) ) {

			$filters = get_object_taxonomies( $typenow );

			foreach ( $filters as $tax_slug ) {

				$tax_obj = get_taxonomy( $tax_slug );

				$args = array(
					'show_option_all' => __( 'Show All ' . $tax_obj->label, 'ignitewoo-wishlists-pro' ),
					'taxonomy' 	  => $tax_slug,
					'name' 		  => $tax_obj->name,
					'orderby' 	  => 'name',
					'hierarchical' 	  => $tax_obj->hierarchical,
					'show_count' 	  => false,
					'hide_empty' 	  => true
				);
				
				if ( isset( $_GET[ $tax_slug ] ) )
					$args['selected'] = $_GET[ $tax_slug ];
					

				wp_dropdown_categories( $args );

			}
		}
	}

	// modify the query vars to handle filtering if question display filtering is in use
	function convert_wishlist_id_to_taxonomy_term_in_query( $query ) {
		global $pagenow, $typenow;

		if ( 'edit.php' == $pagenow && 'custom_wishlists' == $typenow ) {
		
			$filters = get_object_taxonomies( $typenow );

			
			foreach ( $filters as $tax_slug ) {
			
				$var = &$query->query_vars[$tax_slug];
				
				if ( isset( $var ) ) {
				
					$term = get_term_by( 'id', $var, $tax_slug );

					if ( $term )
						$var = $term->slug;
				}
				
			}
		}
	}


	function metaboxes() {
	
		add_meta_box( 'woocommerce-wishlist-customer', __( 'Owner Details', 'ignitewoo-wishlists-pro' ), array( &$this, 'wishlist_items_customer' ), 'custom_wishlists', 'normal', 'high');
		
		add_meta_box( 'woocommerce-wishlist-items', __( 'List Items', 'ignitewoo-wishlists-pro' ), array( &$this, 'wishlist_items_list' ), 'custom_wishlists', 'normal', 'high');



	}
	
	public function wishlist_get_customer_details() {
		ob_start();

		$user_id      = (int) trim(stripslashes($_POST['user_id']));
		
		$type_to_load = esc_attr(trim(stripslashes($_POST['type_to_load'])));

		$customer_data = array(
			$type_to_load . '_first_name' => get_user_meta( $user_id, $type_to_load . '_first_name', true ),
			$type_to_load . '_last_name'  => get_user_meta( $user_id, $type_to_load . '_last_name', true ),
			$type_to_load . '_company'    => get_user_meta( $user_id, $type_to_load . '_company', true ),
			$type_to_load . '_address_1'  => get_user_meta( $user_id, $type_to_load . '_address_1', true ),
			$type_to_load . '_address_2'  => get_user_meta( $user_id, $type_to_load . '_address_2', true ),
			$type_to_load . '_city'       => get_user_meta( $user_id, $type_to_load . '_city', true ),
			$type_to_load . '_postcode'   => get_user_meta( $user_id, $type_to_load . '_postcode', true ),
			$type_to_load . '_country'    => get_user_meta( $user_id, $type_to_load . '_country', true ),
			$type_to_load . '_state'      => get_user_meta( $user_id, $type_to_load . '_state', true ),
			$type_to_load . '_email'      => get_user_meta( $user_id, $type_to_load . '_email', true ),
			$type_to_load . '_phone'      => get_user_meta( $user_id, $type_to_load . '_phone', true ),
		);

		wp_send_json( $customer_data );

	}
	
	function wishlist_items_customer() {
		global $post;
		
		$meta = get_user_meta( $post->post_author );

		?>
		<script>
		jQuery( document ).ready( function($) { 
	
			$( '.load_wishlist_owner_billing' ).on( 'click', function(e) { 
				e.preventDefault();
				
				var user_id = $( '#customer_user' ).val();

				if ( ! user_id ) {
					window.alert( '<?php _e( 'No customer selected', 'ignitewoo-wishlists-pro' ) ?>' );
					return false;
				}

				var data = {
					user_id:      user_id,
					type_to_load: 'billing',
					action:       'wishlist_get_customer_details',
				};

				$( this ).closest( '#wc_wishlist_user' ).block({
					message: null,
					overlayCSS: {
						background: '#fff',
						opacity: 0.6
					}
				});

				$.ajax({
					url: '<?php echo admin_url( 'admin-ajax.php' ) ?>',
					data: data,
					type: 'POST',
					success: function( response ) {
						var info = response;

						if ( info ) {
							$( '.wc_wishlist_user_info .guest_owner' ).hide();
							$( '.wc_wishlist_user_info .first_name' ).html( info.billing_first_name );
							$( '.wc_wishlist_user_info .last_name' ).html( info.billing_last_name );
							$( '.wc_wishlist_user_info .company' ).html( info.billing_company );
							$( '.wc_wishlist_user_info .address_1' ).html( info.billing_address_1 );
							$( '.wc_wishlist_user_info .address_2' ).html( info.billing_address_2 );
							$( '.wc_wishlist_user_info .city' ).html( info.billing_city );
							$( '.wc_wishlist_user_info .postcode' ).html( info.billing_postcode );
							$( '.wc_wishlist_user_info .country' ).html( info.billing_country );
							$( '.wc_wishlist_user_info .state' ).html( info.billing_state );
							$( '.wc_wishlist_user_info .email' ).html( info.billing_email );
							$( 'i.wc_wishlist_user_info .phone' ).html( info.billing_phone );
							$( '.wc_wishlist_user_info .actual_owner' ).show();
						}

						$( '#wc_wishlist_user' ).unblock();
					}
				});
			});
		})
		</script>
		<p id="wc_wishlist_user" class="form-field form-field-wide wc-customer-user">
			<label for="customer_user"><?php _e( 'List Owner:', 'woocommerce' ) ?></label>
			<?php
			$user_string = '';
			$user_id     = '';
			if ( ! empty( $post->post_author ) ) {
				$user_id     = absint( $post->post_author );
				$user        = get_user_by( 'id', $user_id );
				$user_string = esc_html( $user->display_name ) . ' (#' . absint( $user->ID ) . ' &ndash; ' . esc_html( $user->user_email );
			}
			?>
			<input type="hidden" class="wc-customer-search" id="customer_user" name="post_author" data-placeholder="<?php _e( 'Guest', 'woocommerce' ); ?>" data-selected="<?php echo esc_attr( $user_string ); ?>" value="<?php echo $user_id; ?>" data-allow_clear="true" style="width:400px"/> 
			<button class="button load_wishlist_owner_billing" style="margin-top:10px;margin-left: 1em;left:-13px;position:relative;"><?php _e( 'Load billing address', 'woocommerce' ) ?></button>
		</p>
					
		<table class="widefat wc_wishlist_user_info">

			<?php if ( !$meta ) { ?>

			<tr class="guest_owner">
				<td width="20%"><?php _e( 'Guest Wishlist', 'ignitewoo-wishlists-pro' ) ?></td>
				<td><?php _e( 'Random ID', 'ignitewoo-wishlists-pro' ) ?><?php echo $post->post_author ?></td>
			</tr>
			
			<?php } else { ?>
			
			<tr class="actual_owner">
				<td width="20%"><?php _e( 'First name', 'ignitewoo-wishlists-pro' ) ?></td>
				<td class="first_name"><?php echo $meta['billing_first_name'][0] ?></td>
			</tr>
			<tr>
				<td width="20%"><?php _e( 'Last name', 'ignitewoo-wishlists-pro' ) ?></td>
				<td class="last_name"><?php echo $meta['billing_last_name'][0] ?></td>
			</tr>
			<tr>
				<td width="20%"><?php _e( 'Company', 'ignitewoo-wishlists-pro' ) ?></td>
				<td class="company"><?php echo isset( $meta['billing_company'][0] ) ? $meta['billing_company'][0] : '' ?></td>
			</tr>
			<tr>
				<td width="20%"><?php _e( 'Email', 'ignitewoo-wishlists-pro' ) ?></td>
				<td class="email"><?php echo $meta['billing_email'][0] ?></td>
			</tr>
			<tr>
				<td width="20%"><?php _e( 'Phone', 'ignitewoo-wishlists-pro' ) ?></td>
				<td class="phone"><?php echo $meta['billing_phone'][0] ?></td>
			</tr>
			<tr>
				<td width="20%"><?php _e( 'Address 1', 'ignitewoo-wishlists-pro' ) ?></td>
				<td class="address_1"><?php echo $meta['billing_address_1'][0] ?></td>
			</tr>
			<tr>
				<td width="20%"><?php _e( 'Address 2', 'ignitewoo-wishlists-pro' ) ?></td>
				<td class="address_2"><?php echo isset( $meta['billing_address_2'][0] ) ? $meta['billing_address_2'][0] : '' ?></td>
			</tr>
			<tr>
				<td width="20%"><?php _e( 'City', 'ignitewoo-wishlists-pro' ) ?></td>
				<td class="city"><?php echo $meta['billing_city'][0] ?></td>
			</tr>
			<tr>
				<td width="20%"><?php _e( 'State', 'ignitewoo-wishlists-pro' ) ?></td>
				<td class="state"><?php echo $meta['billing_state'][0] ?></td>
			</tr>
			<tr>
				<td width="20%"><?php _e( 'Country', 'ignitewoo-wishlists-pro' ) ?></td>
				<td class="country"><?php echo $meta['billing_country'][0] ?></td>
			</tr>
			<tr>
				<td width="20%"><?php _e( 'Postal Code', 'ignitewoo-wishlists-pro' ) ?></td>
				<td class="postcode"><?php echo $meta['billing_postcode'][0] ?></td>
			</tr>

			<?php } ?>
		</table>
		
		<?php
	}

	
	public function screen_ids( $screen_ids ) {
		$screen_ids[] = 'custom_wishlists';

		return $screen_ids;
	}
	
	function wishlist_items_list() { 
		global $post, $woocommerce, $wpdb, $screen_id;

//		@ini_set( 'display_errors', false );
		
		$items = get_post_meta( $post->ID, 'wishlist_products', true );

		if ( !$items ) 
			$items = array();
			
		echo '<style>.attachment-thumbnail { width: 40px; height: 40px; }</style>';

		echo '<table class="widefat wishlist_items_table">
				<thead>
					<tr>
						<th></th>
						<th>' . __( 'Img', 'ignitewoo-wishlists-pro' ) .'</th>
						<th>' . __( 'Name', 'ignitewoo-wishlists-pro' ) .'</th>
						<th>' . __( 'Price', 'ignitewoo-wishlists-pro' ) . '</th>
						<th>' . __( 'Qty', 'ignitewoo-wishlists-pro' ) . '</th>
						<th>' . __( 'Purchased for the list owner by (if applicable)', 'ignitewoo-wishlists-pro' ) .' </th>
					</tr>
				</thead>
				<tbody>
		';

		$x = 0;
		
		foreach( $items as $item ) { 
		
			if ( empty( $item['id'] ) )
				continue;
				
			$_product = $this->get_product( $item['id'] ); //new WC_Product( $item['id'] );

			$type = '';
			$value = '';

			$product_price = get_option('woocommerce_display_cart_prices_excluding_tax') == 'yes' ? $_product->get_price_excluding_tax() : $_product->get_price();


			if ( '' != $item['vid'] ) {

				$sql = 'select * from ' . $wpdb->postmeta . ' where post_id = ' . $item['vid'] . ' and meta_key like "attribute_%"'; 

				$vals = $wpdb->get_results( $sql );

				if ( $vals )
				foreach( $vals as $v ) { 

					$type = ucwords( str_replace( array( 'attribute_', 'pa_' ), '', $v->meta_key ) );

					$value = ucwords( $v->meta_value );

					break;
				}

				$v = $this->get_product( $item['vid'] ); // new WC_Product_Variation( $item['vid'] );

				if ( !$v ) 
					continue;
					
				$product_price = $v->price;
			}

			echo '<tr class="wl_item_row">
			<td class="wl_item_row_remove" style="color:#cf0000;font-size:1.5em" title="' . __( 'Remove item', 'ignitewoo-wishlists-pro' ) . '">&times</td>
			<td>';

			$image = get_the_post_thumbnail( $item['id'], 'thumbnail' );
			
			if ( empty( $image ) )
				$image = wc_placeholder_img( array( 40,40 ) );

			echo $image; 
			
			echo '</td>';

			echo '<td><a href="' . get_permalink( $item['id'] ) . '" target="_blank">' . get_the_title( $item['id'] ) . '</a>';
			
			if ( $type && $value ) echo '<br/>' . $type . ': ' . $value;
			
			echo '</td>';

			echo '<td>';

				echo apply_filters('woocommerce_cart_item_price_html', woocommerce_price( $product_price ), '', '' ); 

			echo '</td>';

			$purchased = isset( $item['purchased'] ) ? $item['purchased'] : '';
			
			if ( empty( $purchased ) )
				$purchased = '';
				
			$purchased_by = isset( $item['purchased_by'] ) ? $item['purchased_by'] : '';
			
			if ( empty( $purchased_by ) || '' == maybe_unserialize( $purchased_by ) )
				$purchased_by = array();
			?>
			<td>

				<input style="width:40px" type="text" name="wlist_item[qty][<?php echo $x ?>]" value="<?php echo $item['qty'] ?>">
				
				<input type="hidden" name="wlist_item[id][<?php echo $x ?>]" value="<?php echo $item['id'] ?>">
				<input type="hidden" name="wlist_item[vid][<?php echo $x ?>]" value="<?php echo $item['vid'] ?>">
				<input type="hidden" name="wlist_item[purchased][<?php echo $x ?>]" value="<?php echo $purchased ?>">
				<input type="hidden" name="wlist_item[purchased_by][<?php echo $x ?>]" value="<?php echo esc_attr( serialize( $item['purchased_by'] ) ) ?>">
				<input type="hidden" name="wlist_item[show_purchased_by_name][<?php echo $x ?>]" value="<?php echo $item['show_purchased_by_name'] ?>">
				
			</td>
			
			<?php 
			echo '<td><table>';
			

			if ( isset( $item['purchased_by'] ) && '' != $item['purchased_by'] && count($item['purchased_by']) > 0 ) {

				echo '<tr>';
				echo '<th>' . __( 'Buyer', 'ignitewoo-wishlists-pro' ) . '</th>';
				echo '<th>' . __( 'Qty', 'ignitewoo-wishlists-pro' ) . '</th>';
			
				foreach( $item['purchased_by'] as $p ) {

					echo '<tr>';
					
					$user = get_userdata( $p['user'] );
					
					if ( $user ) {
					
						echo '<td>' . get_user_meta( $user->data->ID, 'billing_first_name', true ) . ' ' . get_user_meta( $user->data->ID, 'billing_first_name', true ) . ' (<a href=" ' . admin_url('user-edit.php?user_id=' . $user->data->ID ) .'" target="_blank">' . $user->data->user_login . '</a>)</td>';

						echo '<td>' . $p['qty'] . '</td>';
					}

					echo '</tr>';
				}
			}

			echo '</table></td>';
			echo '</tr>';
			
			$x++;	

		}

		echo '</tbody></table>';
		?>
		
		<p class="form-field add_wishlist_item_p"><label><?php _e( 'Add Products to List', 'woocommerce' ); ?><img class="help_tip" data-tip='<?php _e( 'Add products to this wishlist', 'ignitewoo-wishlists-pro' ); ?>' src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16" /></label><br/>
			<input id="wishlist_product_search" type="hidden" class="wc-product-search" data-wishlistid="<?php echo $post->ID ?>" data-multiple="true" style="width: 50%" name="product_ids" data-placeholder="<?php _e( 'Search for a product&hellip;', 'ignitewoo-wishlists-pro'); ?>" data-action="woocommerce_json_search_products_and_variations" data-selected="[]" value="" />
			<button class="button wc-wishlist-add-products" style="margin-top:10px;margin-left: 1em;left:-13px;position:relative;"><?php _e( 'Add products', 'ignitewoo-wishlists-pro' ); ?></button>
		</p>
		<script>
		jQuery( document ).ready( function($) { 
		
			$( '.wl_item_row_remove' ).on( 'click', function(e) { 
				if ( !confirm( '<?php _e( 'Remove this item? This cannot be undone after you save the post!', 'ignitewoo-wishlists-pro' ); ?>' ) ) 
					return false;
					
				$( this ).closest( 'tr' ).remove();
			})
			
			$( '.wc-product-search' ).val('');
			$( '.wc-product-search' ).data('selected', '[]');
			
			$( '.wc-wishlist-add-products' ).on( 'click', function(e) { 
				e.preventDefault();
				
				var parent_wrap = $( '.add_wishlist_item_p' );				
				var add_item_ids = $( '#wishlist_product_search' ).val();
				$( '#wishlist_product_search' ).select2( 'data', null )

				if ( null == add_item_ids || add_item_ids.length <= 0 || '' == add_item_ids  )
					return false; 
					
				var next_row = $( ' .wishlist_items_table tbody tr.wl_item_row' ).length + 1;

				var data = {
					next_row: next_row,
					add_item_ids: add_item_ids,
					action:        'admin_add_wishlist_item',
				};

				parent_wrap.block();

				$.ajax({
					url: '<?php echo admin_url( 'admin-ajax.php' ) ?>',
					data: data,
					type: 'POST',
					success: function( response ) {
						$( '.wishlist_items_table' ).append( response );
						$( '#wishlist_product_search' ).select2( 'data', null )
						parent_wrap.unblock();
					}
				});
			});
		})
		</script>

		<?php

	}

	public static function admin_add_wishlist_item() {

		$items_to_add = sanitize_text_field( $_POST['add_item_ids'] );

		if ( empty( $items_to_add ) )
			die();

		$items_to_add = explode( ',', $items_to_add );
		
		if ( empty( $items_to_add ) )
			die();
			
		$next_row = absint( $_POST['next_row'] );
			
		foreach( $items_to_add as $id ) { 
			
			$post = get_post( $id );

			if ( ! $post || ( 'product' !== $post->post_type && 'product_variation' !== $post->post_type ) ) {
				continue;
			}

			$_product    = wc_get_product( $post->ID );

			// Set values
			$item = array();

			$item['product_id']        = $_product->id;
			$item['variation_id']      = isset( $_product->variation_id ) ? $_product->variation_id : '';
			$item['variation_data']    = $item['variation_id'] ? $_product->get_variation_attributes() : '';
			$item['name']              = $_product->get_title();
			$item['qty']               = 1;
			$item['link'] 		= get_permalink( $_product->id );
			$item['cost']		= wc_price( $_product->get_price() );
			
			$attrs = '';
			
			if ( !empty( $item['variation_data'] ) ) {
				foreach( $item['variation_data'] as $k => $v ) {
				
					$name = str_replace( array( 'attribute_', 'pa_' ), '', strtolower( $k ) );
					
					$name = ucwords( $name );
					
					$attrs .= '<br>' . $name . ': ' . ucwords( $v );
				
				}
			}

			$fields = '
			<input style="width:40px" type="text" name="wlist_item[qty][' . $next_row . ']" value="1">
			<input type="hidden" name="wlist_item[id][' . $next_row . ']" value="' . $item['product_id']  .'">
			<input type="hidden" name="wlist_item[vid][' . $next_row . ']" value="' . $item['variation_id']  .'">
			<input type="hidden" name="wlist_item[purchased][' . $next_row . ']" value="0">
			<input type="hidden" name="wlist_item[purchased_by][' . $next_row . ']" value="">
			<input type="hidden" name="wlist_item[show_purchased_by_name][' . $next_row . ']" value="">
			';
			
			$out = '
			<tr>
				<td></td>
				<td>
					<a target="_blank" href="'. $item['link'] . '">' . $item['name'] .'</a>' . $attrs . '
				</td>
				<td>
					<span class="amount">' . $item['cost'] . '</span>
				</td>
				<td>' . $fields . '</td>
				<td>
					<table></table>
				</td>
			</tr>
			';
				
			$next_row++;
			
			echo $out; 
		}

		die();
	}
	
	function save_post( $post_id ) {
		global $wpdb;
		
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return;

		if ( empty( $_POST['wlist_item'] ) )
			return;
		
		if ( empty( $_POST['wlist_item']['id'] ) )
			return;
			
		$items = array();

		if ( empty( $purchased_by ) )
			$purchased_by = array();
		
		foreach ( $_POST['wlist_item']['id'] as $i => $item ) { 
		
			$purchased_by = isset( $_POST['wlist_item']['purchased_by'][ $i ] ) ? maybe_unserialize( stripslashes( $_POST['wlist_item']['purchased_by'][ $i ] ) ) : array();
			
			$items[] = array(
				'id' => $_POST['wlist_item']['id'][ $i ],
				'vid' => isset( $_POST['wlist_item']['vid'][ $i ] ) ? $_POST['wlist_item']['vid'][ $i ] : '',
				'qty' => isset( $_POST['wlist_item']['qty'][ $i ] ) ? $_POST['wlist_item']['qty'][ $i ] : '1',
				'purchased' => isset( $_POST['wlist_item']['purchased'][ $i ] ) ? $_POST['wlist_item']['purchased'][ $i ] : array(),
				'purchased_by' => $purchased_by,
				'show_purchased_by_name' => isset( $_POST['wlist_item']['show_purchased_by_name'][ $i ] ) ? $_POST['wlist_item']['show_purchased_by_name'][ $i ] : '',
				
			);

		}

		if ( !empty( $_POST['product_ids'] ) ) {
			$pids = explode( ',', $_POST['product_ids'] );

			if ( !empty( $pids ) ) { 
				foreach( $pids as $p ) { 
				
					$product = wc_get_product( $p );
					
					if ( $product->is_type( 'variation' ) )
						$p = $product->id;
					
					$items[] = array(
						'id' => $p,
						'vid' => isset( $product->variation_id ) ? $product->variation_id : '',
						'qty' => '1',
						'purchased' => array(),
						'purchased_by' => array(),
						'show_purchased_by_name' => '',
						
					);	
				}
			}
		}
		
		update_post_meta( $post_id, 'wishlist_products', $items );

		$author_id = isset( $_POST['post_author'] ) ? $_POST['post_author'] : 1;
		
		$sql = $wpdb->prepare( 'update ' . $wpdb->posts . ' set post_author="%d" where ID=' . $post_id, $author_id );

		$wpdb->query( $sql );
		
	}
	
	function wishlist_search() { 
	    ?>

		<form action="<?php bloginfo('home')?>/" id="searchform" method="get" role="search">
			<div><label for="s" class="screen-reader-text">Search for:</label>
			<input type="text" id="s" name="s" value="">
			<input type="hidden" name="post_type" value="custom_wishlists" />
			<input type="submit" value="<?php _e( 'Search', 'ignitewoo-wishlists-pro' ) ?>" id="searchsubmit">
			</div>
		</form>


	    <?php
	}

	function get_product( $product_id, $args = array() ) {
	
		$product = null;

		if ( version_compare( WOOCOMMERCE_VERSION, "2.3" ) >= 0 ) {
		
			$product = wc_get_product( $product_id, $args );

		} else if ( version_compare( WOOCOMMERCE_VERSION, "2.0.0" ) >= 0 ) {
		
			// WC 2.0
			$product = get_product( $product_id, $args );

		} else {

			// old style, get the product or product variation object
			if ( isset( $args['parent_id'] ) && $args['parent_id'] ) {
			
				$product = new WC_Product_Variation( $product_id, $args['parent_id'] );
				
			} else {
				
				// get the regular product, but if it has a parent, return the product variation object
				$product = new WC_Product( $product_id );

				if ( $product->get_parent() ) {
					$product = new WC_Product_Variation( $product->id, $product->get_parent() );
				}
			}
		}

		return $product;
	}

	/**
	* Safely store data into the session. Compatible with WC 2.0 and
	* backwards compatible with previous versions.
	*
	* @param string $name the name
	* @param mixed $value the value to set
	*/
	private function session_set( $name, $value ) {
		global $woocommerce;

		if ( version_compare( WOOCOMMERCE_VERSION, '2.3', '>=' ) )
			WC()->session->set( $name, $value );
		else if ( isset( $woocommerce->session ) ) {
		// WC 2.0
			$woocommerce->session->$name = $value;
		} else {
			// old style
			$_SESSION[ $name ] = $value;
		}
//var_dump( $name, $value, WC()->session->get( $name ) );
	}
	

	/**
	* Safely retrieve data from the session. Compatible with WC 2.0 and
	* backwards compatible with previous versions.
	*
	* @param string $name the name
	* @return mixed the data, or null
	*/
	function session_get( $name ) {
		global $woocommerce;
//var_dump( WC()->session->get( $name ) );
		if ( version_compare( WOOCOMMERCE_VERSION, '2.3', '>=' ) )
			return WC()->session->get( $name );
		else if ( isset( $woocommerce->session ) ) {
			// WC 2.0
			if ( isset( $woocommerce->session->$name ) )
				return $woocommerce->session->$name;
				
		} else {
			// old style
			if ( isset( $_SESSION[ $name ] ) )
				return $_SESSION[ $name ];
		}

		return '';
	}

	
	/**
	* Safely remove data from the session. Compatible with WC 2.0 and
	* backwards compatible with previous versions.
	*
	* @param string $name the name
	*/
	function session_delete( $name ) {
		global $woocommerce;

		if ( isset( $woocommerce->session ) ) {
			// WC 2.0
			unset( $woocommerce->session->$name );
		} else {
			// old style
			unset( $_SESSION[ $name ] );
		}
	}
	
}

// flush rewrite rules so that custom post type permalinks work 

register_activation_hook( __FILE__, 'ignitewoo_wishlist_flush_rules' );

function ignitewoo_wishlist_flush_rules() { 
	global $wp_rewrite;

	$wp_rewrite->flush_rules();

	flush_rewrite_rules();
}

global $woocom_wishlist; 
$woocom_wishlist = new ignite_woocommerce_wishlist();


add_filter( 'ignitewoo_integrations', 'ignitewoo_wishlist_integration', 999 );

function ignitewoo_wishlist_integration( $integrations ) {

	if ( !class_exists( 'IgniteWoo_Wishlist_Settings' ) )
		require_once( dirname( __FILE__ ) . '/woocommerce-wishlists-admin.php' );
		
	$integrations[] = 'IgniteWoo_Wishlist_Settings';

	return $integrations;
}

	
// Add the plugin settings interface
add_action( 'admin_init', 'ignitewoo_wishlists_admin_init', 1 );

function ignitewoo_wishlists_admin_init() {
	global $ignitewoo_integrations;

	if ( !class_exists( 'Woocommerce' ) && !class_exists( 'WC' ) )
		return;

	// Add the primary tab
	add_action( 'woocommerce_settings_tabs', 'ignitewoo_add_tab', 10 );
	
	// Add the integration sections
	add_action( 'woocommerce_settings_tabs_ignitewoo', 'ignitewoo_settings_tab_action', 10 );
	
	if ( !class_exists( 'IGN_Integration' ) || !class_exists( 'IGN_Integrations' ) ) { 

		require_once( dirname( __FILE__ ) . '/class-ignitewoo-integration.php' );
			
		require_once( dirname( __FILE__ ) . '/class-ignitewoo-integrations.php' );
		
	}
	
	require_once( dirname( __FILE__ ) . '/woocommerce-wishlists-admin.php' );

	$ignitewoo_integrations->init();
	
	if ( !function_exists( 'ignitewoo_add_tab' ) ) { 
	
		function ignitewoo_add_tab() {
			global $ignitewoo_integrations; 

			$current_tab = ( isset($_GET['tab'] ) ) ? $_GET['tab'] : 'general';
			
			$ignitewoo_integrations->ignitewoo_integrations_tab( $current_tab );

		}
	}

	if ( !function_exists( 'ignitewoo_settings_tab_action' ) ) { 
	
		function ignitewoo_settings_tab_action() {
			global $ignitewoo_integrations; 

			$ignitewoo_integrations->ignitewoo_integrations_sections();

		}
	}
}

if ( ! function_exists( 'ignitewoo_queue_update' ) )
	require_once( dirname( __FILE__ ) . '/ignitewoo_updater/ignitewoo_update_api.php' );

$this_plugin_base = plugin_basename( __FILE__ );

add_action( "after_plugin_row_" . $this_plugin_base, 'ignite_plugin_update_row', 1, 2 );

ignitewoo_queue_update( plugin_basename( __FILE__ ), '91316680d8e07169921abd8a5a28d528', '336' );
