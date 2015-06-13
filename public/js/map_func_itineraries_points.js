jQuery(function($) {
	"use strict";
    // Asynchronously Load the map API 
    var script = document.createElement('script');
    script.src = "http://maps.googleapis.com/maps/api/js?sensor=false&callback=initialize";
    document.body.appendChild(script);
});

function initialize() {
    var map;
    var bounds = new google.maps.LatLngBounds();
    var mapOptions = {
        mapTypeId: 'satellite',
		scrollwheel: false
    };
	
                    
    // Display a map on the page
    map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
    map.setTilt(45);
        
    // Multiple Markers
    var markers = [
        ['Site one', 44.666653,7.069874],
        ['Site two', 44.661647,7.077255],
		['Site three', 44.667608,7.091854]
    ];
                        
    // Info Window Content
    var infoWindowContent = [
        ['<div class="info_content">' +
		'<img src="img/site_1.jpg" alt="">' +
        '<h4>Monviso Point A</h4>' +
        '<p> Utamur docendi contentiones per ne, an labore explicari adipiscing eum, tota tempor officiis duo ea.</p>' +'</div>'],
		 ['<div class="info_content">' +
		 '<img src="img/site_2.jpg" alt="">' +
        '<h4>Monviso Point B</h4>' +
        '<p> Utamur docendi contentiones per ne, an labore explicari adipiscing eum, tota tempor officiis duo ea.</p>' +'</div>'],
        ['<div class="info_content">' +
		'<img src="img/site_3.jpg" alt="">' +
        '<h4>Monviso Point C</h4>' +
        '<p> Utamur docendi contentiones per ne, an labore explicari adipiscing eum, tota tempor officiis duo ea.</p>' +'</div>']
    ];
        
    // Display multiple markers on a map
    var infoWindow = new google.maps.InfoWindow(), marker, i;
    
    // Loop through our array of markers & place each one on the map  
    for( i = 0; i < markers.length; i++ ) {
        var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
        bounds.extend(position);
        marker = new google.maps.Marker({
            position: position,
            map: map,
            title: markers[i][0]
        });
        
        // Allow each marker to have an info window    
        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
                infoWindow.setContent(infoWindowContent[i][0]);
                infoWindow.open(map, marker);
            }
        })(marker, i));

        // Automatically center the map fitting all markers on the screen
        map.fitBounds(bounds);
    }

    // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
    var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
        this.setZoom(13);
        google.maps.event.removeListener(boundsListener);
    });
    
}