/**
 * Global Js
 */

jQuery(function ($) {
    $(document).ready(function () {
        initialize();
    }); // End doc ready
    $(window).load(function () {
        $('.flexslider').flexslider({
            animation: "slide",
            animationLoop: false,
            itemWidth: 268,
            itemMargin: 5,
            controlNav: false, //Boolean: Create navigation for paging control of each clide? Note: Leave true for manualControls usage
            directionNav: true, //Boolean: Create navigation for previous/next navigation? (true/false)
            prevText: "", //String: Set the text for the "previous" directionNav item
            nextText: "",
        });
    });
    function initialize() {
        var myLatlngMapCenter = new google.maps.LatLng(40.758023, -73.969549);
        var myLatlngMarkerCenter = new google.maps.LatLng(40.7585431, -73.9697777);
        var myOptions = {
            zoom: 12,
            center: myLatlngMapCenter,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            disableDefaultUI: true
        }

        var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
        var infowindow = new google.maps.InfoWindow({
            content: "<div style='font-family: Roboto,Arial,sans-serif;font-size: 11px;margin: 0;padding: 0;line-height: 15px;'><strong style='font-size: 11px;'>LOREM IPSUM DOLOR SIT AMET</strong><br/>880 3rd Avenue, New York, NY 10022</div>"
        });
        var marker = new google.maps.Marker({
            position: myLatlngMapCenter,
            map: map,
            title: "LOREM IPSUM DOLOR SIT AMET"
        });
        google.maps.event.addListener(marker, 'click', function () {
            infowindow.open(map, marker);
        });
        infowindow.open(map, marker);
        map.setCenter(marker.getPosition());
    }
});