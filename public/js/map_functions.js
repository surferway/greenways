	// Add a marker to the map and push to the array.
	function addMarker(name, lat, lng, contentString, image) {
	  var location = new google.maps.LatLng(lat, lng);
	  var infowindow =  new google.maps.InfoWindow({
            content: contentString
        });	  
	  var marker = new google.maps.Marker({
		position: location,
		title: name,
		map: map,
		icon: image
	  });
	  //google.maps.event.addListener(marker, 'click', function() {
	  //});
	  google.maps.event.addListener(marker, 'mouseover', function() {
        //infowindow.setContent(strDescription);
        infowindow.open(map, marker);
      });
	  
	  google.maps.event.addListener(marker, 'mouseout', function() {
        //infowindow.setContent(strDescription);
        //infowindow.close();
		setTimeout(function () { infowindow.close(); }, 1000);
      });
	  
	  markers.push(marker);
	}
	
	function setBounds(markersArray) {
		var bounds = new google.maps.LatLngBounds();
		for (var i=0; i < markersArray.length; i++) {
			bounds.extend(markersArray[i].getPosition());
		}
		map.fitBounds(bounds);
	}
	
	function addMarkerLocation(location) {
	  var marker = new google.maps.Marker({
		position: location,
		map: map
	  });
	  markers.push(marker);
	}
	
	// Sets the map on all markers in the array.
	function setAllMap(map) {
	  for (var i = 0; i < markers.length; i++) {
		markers[i].setMap(map);
	  }
	}
	
	// Removes the markers from the map, but keeps them in the array.
	function clearMarkers() {
	  setAllMap(null);
	}

	// Shows any markers currently in the array.
	function showMarkers() {
	  setAllMap(map);
	}

	// Deletes all markers in the array by removing references to them.
	function deleteMarkers() {
	  clearMarkers();
	  markers = [];
	}