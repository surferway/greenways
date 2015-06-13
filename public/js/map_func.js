// This example creates a simple polygon representing the Bermuda Triangle.

function initialize() {
"use strict";
  var mapOptions = {
    zoom: 14,
	scrollwheel: false,
    center: new google.maps.LatLng(44.667608,7.091854),
    mapTypeId: google.maps.MapTypeId.TERRAIN
  };

  var bermudaTriangle;

  var map = new google.maps.Map(document.getElementById('map_canvas'),
      mapOptions);

  // Define the LatLng coordinates for the polygon's path.
  var triangleCoords = [
    new google.maps.LatLng(44.666653,7.069874),
    new google.maps.LatLng(44.661647,7.077255),
    new google.maps.LatLng(44.667608,7.091854)
  ];

  // Construct the polygon.
  bermudaTriangle = new google.maps.Polygon({
    paths: triangleCoords,
    strokeColor: '#FF0000',
    strokeOpacity: 0.8,
    strokeWeight: 2,
    fillColor: '#FF0000',
    fillOpacity: 0.35
  });

  bermudaTriangle.setMap(map);
}

google.maps.event.addDomListener(window, 'load', initialize);