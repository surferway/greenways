@extends('layouts.trail')

@section('content')

           
    <h2>
    Edit {{ $trail->name }}
    </h2>
	<p>
		Use the form below to edit your trail.
	</p>

	<span class="failureNotification">
		
	</span>
	<div id="MainContent_RegisterUser_CreateUserStepContainer_RegisterUserValidationSummary" class="failureNotification" style="display:none;">
	</div>
	
	{{ Form::model($trail, ['method' => 'PATCH', 'route' => ['trails.update', $trail->id], 'files' => true]) }}
	<div class="accountInfo">
		<fieldset class="register">
			<legend>Trail Information</legend>
			@if (Auth::user()->hasRole('administrator'))
				<div class="form-group">
				{{ Form::label('active', 'Approved: ') }}
				{{ Form::checkbox('active', 1, null, ['class' => 'field']) }}
				{{ $errors->first('active', '<span class=error>:message</span>') }}
				</div>
			@endif
			<div class="form-group">
			{{ Form::label('name', 'Trail: ') }}
			{{ Form::text('name',null, ['class' => 'form-control style_2']) }}
			{{ $errors->first('name', '<span class=error>:message</span>') }}
			</div>
			<div class="form-group">
			{{ Form::label('location', 'Location: ') }}
			{{ Form::input('text', 'location', null, ['class' => 'form-control style_2'] ) }}
			{{ $errors->first('location', '<span class=error>:message</span>') }}
			</div>	
			<div class="form-group">
			{{ Form::label('duration', 'Average Time: ') }}
			{{ Form::select('duration', ['<1' => '<1','1-2' => '1-2','2-3' => '2-3','3-5' => '3-5','5-7' => '5-7','7-9' => '7-9','9+' => '9+']) }}  &nbsp; hours
			{{ $errors->first('duration', '<span class=error>:message</span>') }}
			</div>	
			
			{{ Form::label('rating', 'Rating: ') }}
			<div class="form-group"  id="rating">
			<div class="rating">
			@foreach ($ratings_array as $rating_key => $rating_val)
				{{ Form::radio('rating', $rating_key, null, ['class' => 'field', 'id' => $rating_val]) }}
				{{ Form::label($rating_val, $rating_val) }}
			@endforeach		
				</div>
			</div>	
			<br/><br/>
			<div class="form-group" id="activities">
			{{ Form::label('activities', 'Activities: ') }}
			@if ($activities->count())
				@foreach ($activities as $activity)
					<label class="checkbox-inline" data-toggle="tooltip" data-placement="bottom" title="{{ $activity->name }}">
					@if (empty($activity_checked))
						{{ Form::checkbox('activities[]', $activity->id, null, ['class' => 'field', 'id' => $activity->name]) }}
					@else
						{{ Form::checkbox('activities[]', $activity->id, in_array($activity->id, $activity_checked), ['class' => 'field', 'id' => $activity->name]) }}					
					@endif
						<label for="{{ $activity->name }}"><img src="{{ asset($activity->button) }}"></label>
					</label>
				@endforeach
			@endif
			</div>			
			<div class="form-group" id="amenities">
			{{ Form::label('amenities', 'Amenities: ') }}
			@if ($amenities->count())
				@foreach ($amenities as $amenity)
					<label class="checkbox-inline" data-toggle="tooltip" data-placement="bottom" title="{{ $amenity->name }}">
					@if (empty($amenity_checked))					
						{{ Form::checkbox('amenities[]', $amenity->id, null, ['class' => 'field', 'id' => $amenity->name]) }}
					@else
						{{ Form::checkbox('amenities[]', $amenity->id, in_array($amenity->id, $amenity_checked), ['class' => 'field', 'id' => $amenity->name]) }}					
					@endif						
					<label for="{{ $amenity->name }}"><img src="{{ asset($amenity->icon) }}"></label>
					</label>
				@endforeach
			@endif
			</div>	
			<div class="form-group"  id="difficulty">
			{{ Form::label('difficulty', 'Difficulty: ') }}
			@if ($difficulties->count())
				@foreach ($difficulties as $difficulty)
					<label class="checkbox-inline" data-toggle="tooltip" data-placement="bottom" title="{{ $difficulty->name }}">
					{{ Form::radio('difficulty', $difficulty->name, null, ['class' => 'field', 'id' => $difficulty->name]) }}
					<label for="{{ $difficulty->name }}"><img src="{{ asset($difficulty->icon) }}"></label>
					</label>
				@endforeach
			@endif
			</div>	
			
			<div class="form-group">
			{{ Form::label('kml_name', 'Replace KML file: ' . $trail->kml_name) }}
			{{ Form::input('file', 'kml_name', null, ['class' => 'textEntry']) }}
			{{ Form::hidden('prev_kml_name', $trail->kml_name)  }}
			{{ $errors->first('kml_name', '<span class=error>:message</span>') }}
			</div>
			
			<div class="form-group"  id="description">
			{{ Form::label('distance', 'Distance: ') }}
			{{ Form::input('text', 'distance', null, ['class' => 'form-control style_2'] ) }}
			{{ Form::label('trail_head_elevation', 'Trail head elevation: ') }}
			{{ Form::input('text', 'trail_head_elevation', null, ['class' => 'form-control style_2'] ) }}
			{{ Form::label('max_elevation', 'Max elevation: ') }}
			{{ Form::input('text', 'max_elevation', null, ['class' => 'form-control style_2'] ) }}
			{{ Form::label('cumulative_elevation', 'Cumulative Elevation: ') }}
			{{ Form::input('text', 'cumulative_elevation', null, ['class' => 'form-control style_2'] ) }}
			{{ Form::label('elevation_gain', 'Elevation Gain: ') }}
			{{ Form::input('text', 'elevation_gain', null, ['class' => 'form-control style_2'] ) }}
			</div>	
			
			<div class="form-group"  id="description">
			{{ Form::label('description', 'Description: ') }}
			{{ Form::input('textarea', 'description', null, ['size' => '30x5', 'class' => 'form-control style_2', 'required' => 'required'] ) }}

			</div>	
			
			<div class="form-group"  id="directions">
			{{ Form::label('directions', 'Directions to Trail Head: ') }}
			{{ Form::input('textarea', 'directions', null, ['class' => 'form-control style_2'] ) }}
			{{ $errors->first('directions', '<span class=error>:message</span>') }}
			</div>	
			
			<div class="form-group"  id="maintenance">
			{{ Form::label('maintenance', 'Planned Maintenance and Trail Events: ') }}
			{{ Form::input('textarea', 'maintenance', null, ['class' => 'form-control style_2'] ) }}
			{{ $errors->first('maintenance', '<span class=error>:message</span>') }}
			</div>	
			
			<div class="form-group"  id="surface">
			{{ Form::label('surface', 'Trail Surface: ') }}
			{{ Form::input('textarea', 'surface', null, ['class' => 'form-control style_2'] ) }}
			{{ $errors->first('surface', '<span class=error>:message</span>') }}
			</div>	
			
			<div class="form-group"  id="season">
			{{ Form::label('season', 'Trail Season: ') }}
			{{ Form::input('text', 'season', null, ['class' => 'form-control style_2'] ) }}
			{{ $errors->first('season', '<span class=error>:message</span>') }}
			</div>	
			
			<div class="form-group"  id="hazards">
			{{ Form::label('hazards', 'Uncommon Hazards: ') }}
			{{ Form::input('textarea', 'hazards', null, ['class' => 'form-control style_2'] ) }}
			{{ $errors->first('hazards', '<span class=error>:message</span>') }}
			</div>	
			
			<div class="form-group"  id="land_access">
			{{ Form::label('land_access', 'Land Access Issues: ') }}
			{{ Form::input('textarea', 'land_access', null, ['class' => 'form-control style_2'] ) }}
			{{ $errors->first('land_access', '<span class=error>:message</span>') }}
			</div>	
			
			<a href="#" role="button" class="btn" data-toggle="modal" data-target="#imagemodal">Add an image</a>
			<div id="image_output" ></div>
			<div id="images">
				@if (count($trail->image) > 0)
					@foreach ($trail->image as $image)
					<div>
						<img src="{{ asset('/uploads/images/' . $image->file) }}" class="preview" alt="{{ $image->name }}"/>
						<input type="hidden" name="imageids[]" value="{{ $image->id }}">
						<button class="delete">Delete</button>
					</div>	
					@endforeach
				@endif
			</div>
			
			<a href="#" role="button" class="btn" data-toggle="modal" data-target="#pointmodal">Add a Point of Interest</a>
			<div id="point_output" ></div>
			<div id="points">
				@if (count($trail->point) > 0)
					@foreach ($trail->point as $point)
					<div>
						<img src="{{ asset('/uploads/points/' . $point->file) }}" class="preview" alt="{{ $point->name }}"/>
						<input type="hidden" name="pointids[]" value="{{ $point->id }}">
						<button class="delete">Delete</button>
					</div>	
					@endforeach
				@endif
			</div>
			
		</fieldset>
		<p class="submitButton">
		{{ Form::submit('Update Trail', ['class' => 'btn btn-default']) }}
		</p>
	</div>
	{{ Form::close() }}
	
<!-- Image modal -->
<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myImagemodal" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myImagemodal">Add an Images</h4>
				
			</div>
			<div class="modal-body">
				<div id="image-loading" style="display:none; margin-left:230px; width:75px;">
					<img src="/img/loading.gif" alt="Loading.." />
				</div>
				<div id="validation-errors"></div>
				{{ Form::open(['id' => 'uploadimage', 'route' => 'trails.imageupload', 'files' => true, 'remote-success-message' => 'Image has been added']) }}
				<div class="form-group">
				{{ Form::label('image_name', 'Image Title: ') }}
				{{ Form::input('text', 'image_name', null, ['class' => 'form-control style_2', 'required' => 'required'] ) }}
				<br/>
				{{ Form::input('file', 'image_file', '', ['class' => 'textEntry']) }}
				</div>
				<p class="submitButton">
				{{ Form::submit('Add Image', ['class' => 'btn btn-default']) }}
				</p>
				{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Image modal -->

<!-- Points of Interest modal -->
<div class="modal fade" id="pointmodal" tabindex="-1" role="dialog" aria-labelledby="myPointmodal" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myImagemodal">Add a Point of Interest</h4>
				
			</div>
			<div class="modal-body">
				<div id="point-loading" style="display:none; margin-left:230px; width:75px;">
					<img src="/img/loading.gif" alt="Loading.." />
				</div>
				<div id="validation-errors"></div>
				{{ Form::open(['id' => 'uploadpoint', 'route' => 'trails.pointupload', 'files' => true, 'remote-success-message' => 'Point of Interest has been added']) }}
				<div class="form-group">
				{{ Form::label('point_name', 'Point of Interest Title: ') }}
				{{ Form::input('text', 'point_name', null, ['class' => 'form-control style_2', 'required' => 'required'] ) }}
				<br/>
				{{ Form::label('point_description', 'Description: ') }}
				{{ Form::input('textarea', 'point_description', null, ['class' => 'form-control style_2', 'required' => 'required'] ) }}
				<br/>
				{{ Form::label('point_name', 'Latitude: ') }}
				{{ Form::input('text', 'point_lat', null, ['class' => 'form-control style_2', 'required' => 'required'] ) }}
				<br/>
				{{ Form::label('point_name', 'Longitude: ') }}
				{{ Form::input('text', 'point_lng', null, ['class' => 'form-control style_2', 'required' => 'required'] ) }}
				<br/>
				{{ Form::input('file', 'point_file', '', ['class' => 'textEntry']) }}
				<br/>
				{{ Form::label('point_primary', 'Primary: ') }}
				{{ Form::checkbox('point_primary', '1', null, ['class' => 'field']) }}
				</div>
				<p class="submitButton">
				{{ Form::submit('Add Point of Interest', ['class' => 'btn btn-default']) }}
				</p>
				{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Points of Interest modal -->
<script>


var elevator;
var path;
var elevations = null;

google.setOnLoadCallback(initialize);

function initialize() {

	// Create an ElevationService.
	elevator = new google.maps.ElevationService();
	
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
  
  if (!(($('#max_elevation').val().length) > 1)){
	$('#max_elevation').val(maxElevation.toFixed(2) + " m");
  }
  if (!(($('#elevation_gain').val().length) > 1)){  
    $('#elevation_gain').val(elevationGain.toFixed(2) + " m");
  }
  if (!(($('#trail_head_elevation').val().length) > 1)){  
    $('#trail_head_elevation').val(trailheadElevation.toFixed(2) + " m"); 
  }
  if (!(($('#cumulative_elevation').val().length) > 1)){  
    $('#cumulative_elevation').val(cumElevation.toFixed(2) + " m");  
  }

  //document.getElementById("max_elevation").value=maxElevation.toFixed(2) + " m"; 
  //document.getElementById("elevation_gain").value=elevationGain.toFixed(2) + " m"; 
  //document.getElementById("trail_head_elevation").value=trailheadElevation.toFixed(2) +" m"; 
  //document.getElementById("cumulative_elevation").value=cumElevation.toFixed(2) + " m"; 
}  
</script>	
		
@stop