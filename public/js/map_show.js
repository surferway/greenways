(function () {

var elevator;
var map;
var path;
var chart;
var ctaLayer;

var mousemarker = null;
var mm_infowindow_open = false;
var infowindow = null;
var elevations = null;

// Load the Visualization API and the columnchart package.
google.load('visualization', '1', {packages: ['columnchart']});
			
function initialize() {
  var invermere = new google.maps.LatLng({{ $trail->start_lat }}, {{ $trail->start_lng }});
  var mapOptions = {
    zoom: 11,
    center: invermere,
	scrollwheel: false,
	mapTypeId: google.maps.MapTypeId.TERRAIN
  }

  map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
  
  // Create a new chart in the elevation_chart DIV.
  chart = new google.visualization.ColumnChart(document.getElementById('elevation_chart'));
  
  ctaLayer = new google.maps.KmlLayer({
    url: 'http://trails.greenways.ca/kml/{{ $trail->kml_name }}'
  });
  ctaLayer.setMap(map);
  
	var weatherLayer = new google.maps.weather.WeatherLayer({
        temperatureUnits: google.maps.weather.TemperatureUnit.CELSIUS
    });
    weatherLayer.setMap(map);

    var cloudLayer = new google.maps.weather.CloudLayer();
    cloudLayer.setMap(map);  
	
	// Create an ElevationService.
	elevator = new google.maps.ElevationService();
	
	infowindow = new google.maps.InfoWindow({});
    google.visualization.events.addListener(chart, 'onmouseover', function(e) {
      if (mousemarker == null) {
        mousemarker = new google.maps.Marker({
          position: elevations[e.row].location,
          map: map,
          icon: "http://maps.google.com/mapfiles/ms/icons/green-dot.png"
        });
        var contentStr = "elevation="+elevations[e.row].elevation+"<br>location="+elevations[e.row].location.toUrlValue(6);
		mousemarker.contentStr = contentStr;
		google.maps.event.addListener(mousemarker, 'click', function(evt) {
		mm_infowindow_open = true;
        infowindow.setContent(this.contentStr);
		infowindow.open(map,mousemarker);
        });
      } else {
        var contentStr = "elevation="+elevations[e.row].elevation+"<br>location="+elevations[e.row].location.toUrlValue(6);
		mousemarker.contentStr = contentStr;
        infowindow.setContent(contentStr);
        mousemarker.setPosition(elevations[e.row].location);
        // if (mm_infowindow_open) infowindow.open(map,mousemarker);
      }	
	
	});
  
	getGeoPath('http://trails.greenways.ca/kml/{{ $trail->kml_name }}');
}

    function getGeoPath(uri){
		jQuery.ajax({
			type: "GET",
			url: uri,
			dataType: "text",
			success: function(data) { 
                //alert("success");
				var xml = jQuery(data);
				var doc = xml.children("Document");
				var kPath = [];

				if (doc.length > 0){
					var coordinates = jQuery(doc).find("LineString").children("coordinates");
					coordinates.each(function(){
						var coords = jQuery(this).text();
						if (coords.length > 0){
							var points = [];
							coord = coords.replace(/\s/g,' ');
							var coordStrings = coord.split( ' ' );
							for ( i = 0; i < coordStrings.length; i++) {
								vals =  coordStrings[i].replace(/[^0-9,.-]/g,'');
								vals = vals.split(',')
								if(!isNaN(vals[0]) && !isNaN(vals[1])){
									loc = new google.maps.LatLng(vals[1], vals[0]);
									points.push(loc); 
								}
							}
							kPath = kPath.concat(points);
						}
					});
					var coords = doc.find('gx\\:Track').find('gx\\:coord');
					if (coords.length > 0){
						var points = [];
						coords.each(function(){
							var coords = jQuery(this).text();
								coord = coords.replace(/\s/g,' ');
								var coordStrings = coord.split( ' ' );
									if(!isNaN(coordStrings[0]) && !isNaN(coordStrings[1])){
										loc = new google.maps.LatLng(coordStrings[1], coordStrings[0]);
										points.push(loc); 
									}
						});
						kPath = kPath.concat(points);
					}
					
				}
				if(kPath.length > 1){					
					if(kPath.length > 100){
						while(kPath.length > 200){
							var i = kPath.length-1;
							for (kPath.length-1; i > 0; i = i-2) 
							{
								kPath.splice(i,1);
							}
						}
					}
					drawPath(kPath);
					//alert(kPath);
				}
			},
			complete:function (jqXHR, textStatus){
				/* enable for error check in loading gpx*/
				if(textStatus == "error"){
					alert('Error: ' + jqXHR.responseText);
				}
			}    
		});	
	}	
	
	function drawPath(path) {

	  // Create a PathElevationRequest object using this array.
	  // Ask for 256 samples along that path.
	  var pathRequest = {
		'path': path,
		'samples': 256
	  }

	  // Initiate the path request.
	  elevator.getElevationAlongPath(pathRequest, plotElevation);
	}
	
// Takes an array of ElevationResult objects, draws the path on the map
// and plots the elevation profile on a Visualization API ColumnChart.
function plotElevation(results, status) {
  if (status != google.maps.ElevationStatus.OK) {
    return;
  }
  elevations = results;

  // Extract the elevation samples from the returned results
  // and store them in an array of LatLngs.
  var elevationPath = [];
  for (var i = 0; i < results.length; i++) {
    elevationPath.push(elevations[i].location);
  }

  // Display a polyline of the elevation path.
  var pathOptions = {
    path: elevationPath,
    strokeColor: '#0000CC',
    opacity: 0.4,
    map: map
  }
  polyline = new google.maps.Polyline(pathOptions);

  // Extract the data from which to populate the chart.
  // Because the samples are equidistant, the 'Sample'
  // column here does double duty as distance along the
  // X axis.
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'Sample');
  data.addColumn('number', 'Elevation');
  for (var i = 0; i < results.length; i++) {
    data.addRow(['', elevations[i].elevation]);
  }

  // Draw the chart using the data within its DIV.
  document.getElementById('elevation_chart').style.display = 'block';
  chart.draw(data, {
    height: 150,
    legend: 'none',
    titleY: 'Elevation (m)'
  });
}

google.maps.event.addDomListener(window, 'load', initialize);
})();