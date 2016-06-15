jQuery(document).ready(function($) {
    jQuery("#event-date").datepicker({ dateFormat: 'dd/mm/yy' }); 
    jQuery(".new-gift-registry").bind( 'click', function(event) { 
    	event.preventDefault();
    	
    }); 
});