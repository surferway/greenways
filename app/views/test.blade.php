@extends('layouts.trail')

@section('content')
	<h2>Test</h2>
	This is a test
	
	<script>  
	

var elevator;
var path;

			
function initialize() {

// Create an ElevationService.
  elevator = new google.maps.ElevationService();

	getGeoPath("http://trails.greenways.ca/kml/Kloosifier.kml");

}

    function getGeoPath(uri){
		jQuery.ajax({
			type: "GET",
			url: uri,
			dataType: "text",
			success: function(data) { 
                alert("success");
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
  var elevations = results;

  // Extract the elevation samples from the returned results
  // and store them in an array of LatLngs.
  var maxElevation = 0;
  var minElevation = 10000; 
  var altDiff = 0;
  var cumElevation = 0;
  var elevationGain = 0;
  var trailheadElevation = elevations[0].elevation;

  var elevationData = [];
  for (var i = 0; i < results.length; i++) {
    elevationData.push(elevations[i].elevation);
	if (elevations[i].elevation > maxElevation){
		maxElevation = elevations[i].elevation;
	}
	if (elevations[i].elevation < minElevation){
		minElevation = elevations[i].elevation;
	}	
	if (i > 1){
		altDiff = elevations[i].elevation - elevations[i-1].elevation;
		cumElevation += Math.max(0, altDiff);
	}
  }
  elevationGain = maxElevation - minElevation;
  
  alert('Max Elevation is ' + maxElevation.toFixed(2));
  alert('Cumulative Elevation is ' + cumElevation.toFixed(2)); 
  alert('Elevation Gain is ' + elevationGain.toFixed(2));   
  alert('Trail Head Elevation is ' + trailheadElevation.toFixed(2));  
  alert('Elevation count ' + elevationData.length);
  
 // alert(elevationData.toString());


}
    
window.onload = function()
{
	initialize();
}
	

   </script>  
   <div id="elevation_chart"></div>	
@stop