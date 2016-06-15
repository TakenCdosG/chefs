jQuery(document).ready(function($) {
	function requestFormAddWishList(){
		var nonce = chef_gift_registry.wishlist_nonce;
		jQuery.ajax({
			type	: "POST",
			cache	: false,
			url		: chef_gift_registry.admin_url,
			data	: { nonce: nonce, user: chef_gift_registry.user_id, action: 'chef_gift_registry_action'  },
			success: function( data ) {
				$( '#wishlist_box_wrapper' ).html( data );		
				$( '#wishlist_add_hidden_link' ).trigger( 'click' );
			}
		});
		return false;
	}

	function showAddFormWishList(){
		$("#wishlist_add_hidden_link").prettyPhoto({
			hook: 'data-rel',
			social_tools: false,
			theme: 'pp_woocommerce',
			horizontal_padding: 20,
			opacity: 0.8,
			deeplinking: false,
			default_height: '200px',	
			changepicturecallback: function() {

				$( 'button#wishlist_add_button' ).on( 'click', function() {

					var args = jQuery('form#wishslist_entry_form').serialize();
					jQuery.ajax({
						type	: "POST",
						cache	: false,
						url		: chef_gift_registry.admin_url,
						data	: args,
						success: function(data) {
							$( '.pp_overlay' ).trigger( 'click' );
							setTimeout( function() { 
								$( 'div#wishlist_box_wrapper' ).html( data );
								$( '#wishlist_add_hidden_link' ).trigger( 'click' );
							}, 1000 );
						}
					});

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
	}

    $("#event-date").datepicker({ dateFormat: 'dd/mm/yy' }); 

    $(".new-gift-registry").bind( 'click', function(event) { 
    	event.preventDefault();
    	var is_user_logged_in = chef_gift_registry.is_user_logged_in;
    	if(is_user_logged_in == "TRUE"){
			showAddFormWishList();
    		requestFormAddWishList();
		}else{
			
		}
    }); 
});