jQuery(document).ready(function($) {
    jQuery("#event-date").datepicker({ dateFormat: 'dd/mm/yy' }); 
    jQuery(".new-gift-registry").bind( 'click', function(event) { 
    	event.preventDefault();
    	var is_user_logged_in = chef_gift_registry.is_user_logged_in;
    	console.log(is_user_logged_in);
    }); 
});