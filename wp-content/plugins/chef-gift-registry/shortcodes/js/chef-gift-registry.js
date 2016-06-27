jQuery(document).ready(function($) {

	function handleFormValitations(){

		var form = $(".pp_woocommerce form#wishslist_entry_form");

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
			modal: true,
			changepicturecallback: function() {
				console.log("> Open Modal.");
				$("#wishslist_entry_form #event-date-inside-modal").datepicker(
					{ 
						dateFormat: 'dd/mm/yy',
					    onSelect: function(dateText, datePicker) {
					       $(this).attr('value', dateText);
					       $(this).val(dateText);
					       $("#wishslist_entry_form #event-date-inside-modal").val(dateText);
					       console.log("Seteando Valor:"+ dateText);
					    }
				    }
				);

				$( 'button#wishlist_add_button' ).on( 'click', function() {
					if(handleFormValitations()){
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
	}

    $("#event-date").datepicker({ dateFormat: 'dd/mm/yy' }); 

    $(".new-gift-registry").bind( 'click', function(event) { 
    	event.preventDefault();
    	var is_user_logged_in = chef_gift_registry.is_user_logged_in;
    	if(is_user_logged_in == "TRUE"){
			showAddFormWishList();
    		requestFormAddWishList();
		}else{
			window.location = chef_gift_registry.redirect_url;
		}
    }); 

    if(chef_gift_registry.redirect_action == "AddRegistry"){
    	$( '.new-gift-registry' ).trigger( 'click' );
    }
    
});