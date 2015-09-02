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
        initialize();
        $('ul.products').infinitescroll({
            navSelector  : "div.pagination-category",
            // selector for the paged navigation (it will be hidden)
            nextSelector : "div.pagination-category a:first",
            // selector for the NEXT link (to page 2)
            itemSelector : "ul.products li.product",
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

        /*
        $('.images .thumbnails').flexslider({
            selector: "a.zoom",
            animation: "slide",
            animationLoop: false,
            itemWidth: 194,
            itemMargin: 5,
            controlNav: false,
            directionNav: true,
            prevText: "",
            nextText: "",
            animationLoop: true,
            move: 1
        });
        */

    });
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
            content: "<div style='font-family: Roboto,Arial,sans-serif; font-size: 10px; margin: 0;padding: 0;line-height: 15px;'><strong style='font-size: 11px; '>Chef's Emporium</strong><br/>449 Boston Post Rd, Orange, CT 06477</div>"
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
});