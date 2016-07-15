/**
 * Global Js
 */

jQuery(function ($) {

    /*
     *  render_map
     *
     *  This function will render a Google Map onto the selected jQuery element
     *
     *  @type	function
     *  @date	8/11/2013
     *  @since	4.3.0
     *
     *  @param	$el (jQuery element)
     *  @return	n/a
     */
    function render_map($el) {
        // var
        var $markers = $el.find('.marker');
        // vars
        var args = {
            zoom		: 16,
            center		: new google.maps.LatLng(0, 0),
            mapTypeId	: google.maps.MapTypeId.ROADMAP
        };
        // create map
        var map = new google.maps.Map( $el[0], args);
        // add a markers reference
        map.markers = [];
        // add markers
        $markers.each(function(){
            add_marker( $(this), map );
        });
        // center map
        center_map( map );
    }
    /*
     *  add_marker
     *
     *  This function will add a marker to the selected Google Map
     *
     *  @type	function
     *  @date	8/11/2013
     *  @since	4.3.0
     *
     *  @param	$marker (jQuery element)
     *  @param	map (Google Map object)
     *  @return	n/a
     */
    function add_marker($marker,map) {
        // var
        var latlng = new google.maps.LatLng($marker.attr('data-lat'), $marker.attr('data-lng') );
        // create marker
        var marker = new google.maps.Marker({
            position	: latlng,
            map			: map
        });
        // add to array
        map.markers.push(marker);
        // if marker contains HTML, add it to an infoWindow
        if($marker.html()){
            // create info window
            var infowindow = new google.maps.InfoWindow({
                content		: $marker.html()
            });
            // show info window when marker is clicked
            google.maps.event.addListener(marker, 'click', function() {
                infowindow.open( map, marker );
            });
            // show info window by default
            infowindow.open( map, marker );
        }
    }
    /*
     *  center_map
     *
     *  This function will center the map, showing all markers attached to this map
     *
     *  @type	function
     *  @date	8/11/2013
     *  @since	4.3.0
     *
     *  @param	map (Google Map object)
     *  @return	n/a
     */
    function center_map( map ) {
        // vars
        var bounds = new google.maps.LatLngBounds();
        // loop through all markers and create bounds
        $.each( map.markers, function( i, marker ){
            var latlng = new google.maps.LatLng( marker.position.lat(), marker.position.lng() );
            bounds.extend( latlng );
        });
        // only 1 marker?
        if( map.markers.length == 1 )
        {
            // set center of map
            map.setCenter( bounds.getCenter() );
            map.setZoom( 16 );
        }
        else
        {
            // fit to bounds
            map.fitBounds( bounds );
        }

    }

    // Mobile menu
    // $('ul.main-menu').mobileMenu();
    var combinedMenu = $('#main-nav ul.root').clone();
    var searchform = $('.searchform').clone();
    var secondMenu = $('ul#menu-header-bottom').clone();
    var thirdMenu = $('ul#menu-header-upper').clone();

    combinedMenu.addClass("menu-left");
    secondMenu.addClass("category2 slicknav_nav hide menu-right");
    secondMenu.removeAttr("id");

    //secondMenu.appendTo(combinedMenu);
    thirdMenu.addClass("category3 slicknav_nav hide menu-right");
    thirdMenu.removeAttr("id");
    //thirdMenu.appendTo(combinedMenu);
    combinedMenu.slicknav({
        duplicate:false,
        prependTo : '.mobile-menu',
        label: 'MENU',
        allowParentLinks: true
    });

    // Second Menu.
    $("ul.slicknav_nav").after(secondMenu);
    // Third Menu.
    $("ul.category2").after(thirdMenu);
    $("a.slicknav_btn").click(function() {
        $("ul.category2").slideToggle( "fast", function() {
            // Animation complete.
        });
        $("ul.category3").slideToggle( "fast", function() {
            // Animation complete.
        });
    });


    $('.mobile-menu').prepend(searchform);

    /*
     *  document ready
     *
     *  This function will render each map when the document is ready (page has loaded)
     *
     *  @type	function
     *  @date	8/11/2013
     *  @since	5.0.0
     *
     *  @param	n/a
     *  @return	n/a
     */

    $(document).ready(function () {
        accordion();
        initialize();
        $('ul.products').infinitescroll({
            loading: {
                finished: undefined,
                finishedMsg: "",
                msgText: "<em>Loading the next set of products...</em>",
                speed: 'fast',
                start: undefined
            },
            navSelector  : "div.pagination-category",
            // selector for the paged navigation (it will be hidden)
            nextSelector : "div.pagination-category a:first",
            // selector for the NEXT link (to page 2)
            itemSelector : "ul.products li",
            // selector for all items you'll retrieve
            debug : true,
            // enable debug messaging ( to console.log )
            loadingImg   : "/img/loading.gif",
            // loading image.
            loadingText  : "Loading new posts...",
            donetext     : "You've reached the end of the products for this category.",
            loadingText  : "Loading new products...",
            animate      : false
        });

        $('.acf-map').each(function(){
            render_map( $(this) );
        });
        jQuery("a[data-rel^='prettyPhoto[product-gallery]']").click(function() {
            event.preventDefault();
            //console.log( "Handler for .click() called." );
            jQuery("a[rel^='prettyPhoto[product-gallery]']").prettyPhoto();
        });
    }); // End doc ready
    $(window).load(function () {
        $('.flexslider').flexslider({
            animation: "slide",
            animationLoop: false,
            itemWidth: 194,
            itemMargin: 5,
            controlNav: false, //Boolean: Create navigation for paging control of each clide? Note: Leave true for manualControls usage
            directionNav: true, //Boolean: Create navigation for previous/next navigation? (true/false)
            prevText: "", //String: Set the text for the "previous" directionNav item
            nextText: "",
            animationLoop: true,
            move: 1
        });
    });
    function accordion(){
        $( "#accordion" ).accordion({
             active: 0,
             collapsible: true,
             autoHeight: false
        });
        $( "#accordion div").css({ 'height': 'auto' });

    }
    function initialize() {
        var myLatlngMapCenter = new google.maps.LatLng(41.257697, -73.013472);
        var myLatlngMarkerCenter = new google.maps.LatLng(41.259275, -73.024664);
        var myOptions = {
            zoom: 12,
            center: myLatlngMapCenter,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            disableDefaultUI: true
        }

        var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
        var infowindow = new google.maps.InfoWindow({
            content: "<div style='font-family: Roboto,Arial,sans-serif; font-size: 10px; margin: 0;padding: 0;line-height: 15px;'><strong style='font-size: 11px; '>Chef's Emporium</strong><br/><a target='_blank' href='https://www.google.com/maps/place/449+Boston+Post+Rd,+Orange,+CT+06477,+EE.+UU./@41.2576974,-73.0134718,17z/data=!3m1!4b1!4m2!3m1!1s0x89e875c119620cdf:0x5bae5e90ee506a94'>449 Boston Post Road Orange, CT 06477</a></div>"
        });
        var marker = new google.maps.Marker({
            position: myLatlngMapCenter,
            map: map,
            title: "Chef's Emporium"
        });
        google.maps.event.addListener(marker, 'click', function () {
            infowindow.open(map, marker);
        });
        infowindow.open(map, marker);
        map.setCenter(marker.getPosition());
    }
    $('#wishlist_table > tbody  > tr').each(function() {
       if(!$(this).find(".qty").length){
           $(this).find(".button").addClass("hide-me");
       }
    });
});