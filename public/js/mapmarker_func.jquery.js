			//set up markers 
		var myMarkers = {"markers": [
				{"latitude": "44.62105", "longitude":"7.029963", "icon": "img/map-marker2.png"}
			]
		};
		
		//set up map options
		$("#map").mapmarker({
			zoom	: 10,
			center	: 'Pontechianale, Italy',
			markers	: myMarkers
		});

