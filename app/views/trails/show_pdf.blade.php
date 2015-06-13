<!DOCTYPE html>
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if IE 9 ]><html class="ie ie9" lang="en"> <![endif]-->
<html lang="en">
<!--<![endif]-->
<head>
	<!-- Basic Page Needs -->
<meta charset="utf-8">
<title>Columbia Valley Greenways Trail Alliance</title>
<meta name="description" content="The Columbia Valley Greenways Trail Alliance envisions the region between Canal Flats and Donald as connected by land and water greenways.">
<meta name="author" content="Columbia Valley Greenways Trail Alliance">


<!-- CSS -->
<style>
html {
font-family: sans-serif;
-ms-text-size-adjust: 100%;
-webkit-text-size-adjust: 100%;
}

body {
font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
font-size: 14px;
line-height: 1.42857143;
color: #333;
background-color: #fff;
}

.container {
margin-right: auto;
margin-left: auto;
padding-left: 15px;
padding-right: 15px;
}

.row {
margin-left: -15px;
margin-right: -15px;
}

.col-md-12 {
width: 100%;
float: left;
position: relative;
min-height: 1px;
padding-left: 15px;
padding-right: 15px;
}

.list-inline>li {
display: inline-block;
padding-left: 5px;
padding-right: 5px;
}

.list-inline {
padding-left: 0;
list-style: none;
margin-left: -5px;
}

ul, ol {
margin-top: 0;
margin-bottom: 10px;
}

.glyphicon {
position: relative;
top: 1px;
display: inline-block;
font-family: 'Glyphicons Halflings';
font-style: normal;
font-weight: 400;
line-height: 1;
-webkit-font-smoothing: antialiased;
-moz-osx-font-smoothing: grayscale;
}
#map-canvas {
	height: 400px;
	width: 900px;		
	margin: 20px;
	padding: 20px
}
</style>

<script src="http://trails.greenways.ca/js/jquery-1.11.1.min.js"></script>

<script src="https://www.google.com/jsapi"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=weather"></script>
<script type="text/javascript" src="http://geoxml3.googlecode.com/svn/branches/polys/geoxml3.js"></script>
<script type="text/javascript" src="http://geoxml3.googlecode.com/svn/trunk/ProjectedOverlay.js"></script>
</head>
<body>


<div class="container">
<div class="row">
		<div class="col-md-12">


		<h2>{{ $trail->name }}</h2>
		
		<br/>
		@for ($i=1; $i<=$trail->rating; $i++)
				<span class="glyphicon glyphicon-star"></span>
		@endfor	
		<br/>
		@if ($trail->organization)
			{{ $trail->organization->organization }}
		@endif
		<br/>
		{{ $trail->location }}
		<br/>
		
		@if (count($trail->activity) > 0)
			<ul class="list-inline">
			@foreach ($trail->activity as $activity)
				<li>
				<label data-toggle="tooltip" data-placement="bottom" title="{{ $activity->name }}">
				<img src="{{ asset($activity->icon) }}" class="img-responsive inline-block" alt="{{ $activity->name }}"/>
				</label>
				</li>			
			@endforeach
			</ul>
		@endif

		
		<ul class="list-inline"> 
		<li>
		{{ number_format($trail->distance, 2) }}<br>
		Distance
		<br><img src="{{ asset('images/trail/distance.png') }}" height="55" >
		</li>
		<li>
		<div id="elevation_gain">
			{{ $trail->elevation_gain }}
		</div>
		Elevation Gain
		<br><img src="{{ asset('images/trail/elevation_gain.png') }}" height="55" >
		</li>	
		<li>
		<div id="trail_head_elevation">
			{{ $trail->trail_head_elevation }}
		</div>
		Trail Head Elevation
		<br><img src="{{ asset('images/trail/trail_head_elevation.png') }}" height="55" >
		</li>	
		<li>
		<div id="max_elevation">
			{{ $trail->max_elevation }}
		</div>
		Maximum Elevation
		<br><img src="{{ asset('images/trail/max_elevation.png') }}" height="55" >
		</li>	
		<li>
		<div id="cumulative_elevation">
			{{ $trail->cumulative_elevation }}
		</div>
		Cumulative Elevation
		<br><img src="{{ asset('images/trail/cumulative_elevation.png') }}" height="55">
		</li>
		<li>
		{{ $trail->duration }}<br>
		Average Time
		<br><img src="{{ asset('images/trail/duration.png') }}" height="55" >
		</li>
		</ul>
		
		@if (count($trail->image) > 0)
		<br/><br/>
			<div id="owl-demo">
			@foreach ($trail->image as $image)
				<div class="item"><img src="{{ asset('/uploads/images/' . str_replace(' ', '%20',$image->file)) }}" alt="{{ $image->name }}" width="150"></div>
				@endforeach
			</div>
		@endif
				
		
		@if ($trail->description)
		<br>
		<br><img src="{{ asset('images/trail/description.png') }}"  height="55"">
		<!-- class="img-responsive" alt="Responsive image" -->
		<h4>Trail Description</h4>
		<p>
			{{ $trail->description }}
		</p>
		@endif
		
		@if ($trail->directions)
		<br><img src="{{ asset('images/trail/directions.png') }}"  height="55"">
		<!-- class="img-responsive" alt="Responsive image" -->
		<h4>Directions to Trail Head</h4>
		<p>
			{{ $trail->directions }}
		</p>
		@endif
		
		<br><img src="{{ asset('images/trail/detailed_map.png') }}"  height="55"">
		<!-- class="img-responsive" alt="Responsive image" -->
		<h4>Detailed Map</h4>

		
		
		    <script>
	
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
  var maxElevation = 0;
  var minElevation = 10000; 
  var altDiff = 0;
  var cumElevation = 0;
  var elevationGain = 0;
  var trailheadElevation = elevations[0].elevation;
  
  var elevationPath = [];
  for (var i = 0; i < results.length; i++) {
    elevationPath.push(elevations[i].location);
	
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
  
  document.getElementById("max_elevation").innerHTML=maxElevation.toFixed(2); 
  document.getElementById("elevation_gain").innerHTML=elevationGain.toFixed(2); 
  document.getElementById("trail_head_elevation").innerHTML=trailheadElevation.toFixed(2); 
  document.getElementById("cumulative_elevation").innerHTML=cumElevation.toFixed(2); 
  
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
    </script>
	
		<div id="map-canvas"></div>

		<br><img src="{{ asset('images/trail/elevation.png') }}"  height="55"">
		<!-- class="img-responsive" alt="Responsive image" -->
		<h4>Elevation Profile</h4>
		<div id="elevation_chart"></div>	

		@if ($trail->maintenance)
			<br><img src="{{ asset('images/trail/maintenance.png') }}"  height="55"">
			<!-- class="img-responsive" alt="Responsive image" -->
			<h4>Planned Mainenance and Trail Events</h4>
			<p>{{ $trail->maintenance }}</p>
		@endif
		
		@if ($trail->surface)
			<br><img src="{{ asset('images/trail/trail_surface.png') }}"  height="55"">
			<!-- class="img-responsive" alt="Responsive image" -->
			<h4>Trail Surface</h4>
			<p>{{ $trail->surface }}</p>
		@endif
		
		@if ($trail->season)
			<br><img src="{{ asset('images/trail/trail_season.png') }}"  height="55"">
			<!-- class="img-responsive" alt="Responsive image" -->
			<h4>Trail Season</h4>
			<p>{{ $trail->season }}</p>
		@endif
		
		@if ($trail->surface)
			<br><img src="{{ asset('images/trail/points.png') }}"  height="55"">
			<!-- class="img-responsive" alt="Responsive image" -->
			<h4>Points of Interest</h4>
		@endif
		
		@if ($trail->open)
			<br><img src="{{ asset('images/trail/approved.png') }}"  height="55"">
			<!-- class="img-responsive" alt="Responsive image" -->
			<h4>Sanctioned Status</h4>
			<p>{{ $trail->open }}</p>
		@endif
		
		@if ($trail->hazards)
			<br><img src="{{ asset('images/trail/hazards.png') }}"  height="55"">
			<!-- class="img-responsive" alt="Responsive image" -->
			<h4>Uncommon Hazards</h4>
			<p>{{ $trail->hazards }}</p>
		@endif
		
		@if ($trail->land_access)
			<br><img src="{{ asset('images/trail/land_access.png') }}"  height="55"">
			<!-- class="img-responsive" alt="Responsive image" -->
			<h4>Land Access Issues</h4>
			<p>{{ $trail->land_access }}</p>
		@endif
		
		@if (count($trail->amenity) > 0)
		<br/>
		<h4>Trail Head Features</h4>
			<ul class="list-inline">
			@foreach ($trail->amenity as $amenity)
				<li>
				<label data-toggle="tooltip" data-placement="bottom" title="{{ $amenity->name }}">
				<img src="{{ asset($amenity->icon) }}" class="img-responsive inline-block" alt="{{ $amenity->name }}"/>
				</label>
				</li>			
			@endforeach
			</ul>
		@endif
		
		<img src="https://api.qrserver.com/v1/create-qr-code/?data={{ rawurlencode("http://trails.greenways.ca/trails/" . $trail->id)}}&size=100x100" alt="" title="" />
	
			</div><!-- End col-md-12 -->
	</div><!-- End row -->
</div><!-- End container -->

</body>
</html>