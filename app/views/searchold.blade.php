@extends('layouts.search')

@section('content')
<script type="text/javascript">



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
	  
	  $("#duration-slider").slider({});
	  
	  $("#distance-slider").slider({});
	  
	}

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
	  google.maps.event.addListener(marker, 'click', function() {
        //infowindow.setContent(strDescription);
        infowindow.open(map, marker);
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
	
	google.maps.event.addDomListener(window, 'load', initialize);
	
	
</script>	

	<h2>Trails Search</h2>
	
	<div class="row">
		<div class="col-md-4">
		
			{{ Form::open(['data-remote-success-message' => 'All Done']) }}
			
			<div class="form-group" id="activity_search">
			@if ($activities->count())
				@foreach ($activities as $activity)
					<label class="checkbox-inline" data-toggle="tooltip" data-placement="bottom" title="{{ $activity->name }}">
					@if (empty($activity_checked))
						{{ Form::checkbox('activities[]', $activity->id, null, ['class' => 'field', 'id' => $activity->name]) }}
					@else
						{{ Form::checkbox('activities[]', $activity->id, in_array($activity->id, $activity_checked), ['class' => 'field', 'id' => $activity->name]) }}					
					@endif
					<label for="{{ $activity->name }}"><img src="{{ asset($activity->icon) }}" width="50px"></label>
					</label>
				@endforeach
			@endif
			<br/>
			{{ Form::label('activities', 'Select Activity Type ') }}
			</div>	
			
			<div class="form-group"  id="difficulty_search">
			@if ($difficulties->count())
				@foreach ($difficulties as $difficulty)
					<label class="checkbox-inline" data-toggle="tooltip" data-placement="bottom" title="{{ $difficulty->name }}">
					{{ Form::checkbox('difficulty', $difficulty->name, null, ['class' => 'field', 'id' => $difficulty->name]) }}
					<label for="{{ $difficulty->name }}"><img src="{{ asset($difficulty->icon) }}"></label>
					</label>
				@endforeach
			@endif
			<br/>
			{{ Form::label('difficulty', 'Select Difficulty') }}
			</div>	
			
			<div class="form-group"  id="duration_search">
			0 hours 
			<input id="duration-slider" type="text" class="span2" value="" data-slider-min="0" data-slider-max="8" data-slider-step="1" data-slider-value="[1,3]"/>
			8+ hours
			<br/>
			{{ Form::label('duration', 'Select Duration') }}
			</div>	
			
			<div class="form-group"  id="distance_search">
			1 km 
			<input id="distance-slider" type="text" class="span2" value="" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="[0,10]"/>
			100km
			<br/>
			{{ Form::label('distance', 'Select Distance') }}
			</div>	

			<!--
			<div class="form-group">
			{{ Form::submit('Find Your Trail', ['class' => 'btn btn-default']) }}
			</div>
			-->
				

			{{ Form::close() }}
			
		</div>
		<div class="col-md-8">
		<div id="map-area"></div>	
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<!-- Trails Table -->
			<div id="trails_table">
			
			</div>
			<!-- Trails Table -->
		</div>
	</div>
	
		


@stop