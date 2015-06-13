	var map;
	var markers = [];
	var infoContent = "";

	function initialize() {
	  var invermere = new google.maps.LatLng(50.508368, -116.031534);
	  var mapOptions = {
		zoom: 8,
		center: invermere,
		scrollwheel: false,
		mapTypeId: google.maps.MapTypeId.TERRAIN
	  };
	  map = new google.maps.Map(document.getElementById('map-area'),
		  mapOptions);

	  // This event listener will call addMarker() when the map is clicked.
	  google.maps.event.addListener(map, 'click', function(event) {
		addMarkerLocation(event.latLng);
	  });
	  
	  //marker = Greenways.markers[1];
	  //alert(marker['name']);
	  var image = 'images/activities/marker-hike.png';
	  Greenways.markers.forEach(function(marker){
		if (marker['lat'] > 0){
			infoContent = "<a href='http://trails.greenways.ca/trails/" + String(marker['name']) + "'>" + String(marker['name']) + "</a> <br> " + String(marker['level']);
			//alert(String(marker['name']) + " " + marker['lat'] + " " + marker['lng']);
			addMarker(String(marker['name']), marker['lat'], marker['lng'], infoContent, image);
		}
	  });
	  
	  setBounds(markers);

	  // Adds a marker at the center of the map.
	  //addMarker('Invermere', '50.508368', '-116.031534');
	  
	}

	
	google.maps.event.addDomListener(window, 'load', initialize);