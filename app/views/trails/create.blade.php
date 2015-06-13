@extends('layouts.default')

@section('content')

           
    <h2>
    Create a New Trail
    </h2>
	<p>
		Use the form below to create a new trail.
	</p>

	<span class="failureNotification">
		
	</span>
	<div id="MainContent_RegisterUser_CreateUserStepContainer_RegisterUserValidationSummary" class="failureNotification" style="display:none;">
	</div>

	
	{{ Form::open(['id' => 'createtrail', 'route' => 'trails.store', 'files' => true, 'novalidate' => '']) }}
	<div class="accountInfo">
		<fieldset class="register">
			<legend>Trail Information</legend>
			<div class="form-group">
			{{ Form::label('name', 'Trail: ') }}
			{{ Form::input('text', 'name', null, ['class' => 'form-control style_2', 'required' => 'required', 'placeholder' => ' Enter Trail Name'] ) }}
			{{ $errors->first('name', '<span class="alert alert-error">:message</span>') }}
			</div>
			<div class="form-group">
			{{ Form::label('location', 'Location: ') }}
			{{ Form::input('text', 'location', null, ['class' => 'textEntry', 'required' => 'required'] ) }}
			{{ $errors->first('location', '<span class="alert alert-error">:message</span>') }}
			</div>	
			<div class="form-group">
			{{ Form::label('duration', 'Average Time: ') }}
			{{ Form::select('duration', ['<1' => '<1','1-2' => '1-2','2-3' => '2-3','3-5' => '3-5','5-7' => '5-7','7-9' => '7-9','9+' => '9+']) }}  &nbsp; hours
			{{ $errors->first('duration', '<span class="alert alert-error">:message</span>') }}
			</div>				
			
			{{ Form::label('rating', 'Rating: ') }}
			<div class="form-group"  id="rating">
			<div class="rating">
			@if ($ratings_array = get_ratings()) @endif
			@foreach ($ratings_array as $rating_key => $rating_val)
				{{ Form::radio('rating', $rating_key, null, ['class' => 'field', 'id' => $rating_val]) }}
				{{ Form::label($rating_val, $rating_val) }}
			@endforeach	
			{{ $errors->first('rating', '<span class="alert alert-error">:message</span>') }}
				</div>
			</div>	
				
			<br/><br/>			
			<div class="form-group" id="activities">
			{{ Form::label('activities', 'Activities: ') }}
			@if ($activities->count())
				@foreach ($activities as $activity)
					<label class="checkbox-inline" data-toggle="tooltip" data-placement="bottom" title="{{ $activity->name }}">
					{{ Form::checkbox('activities[]', $activity->id, null, ['class' => 'field', 'id' => $activity->name]) }}
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
					{{ Form::checkbox('amenities[]', $amenity->id, null, ['class' => 'field', 'id' => $amenity->name]) }}
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
			{{ $errors->first('difficulty', '<span class="alert alert-error">:message</span>') }}
			</div>	
			<div class="form-group">
			{{ Form::label('kml_name', 'KML: ') }}
			{{ Form::input('file', 'kml_name', null, ['class' => 'textEntry']) }}
			{{ $errors->first('kml_name', '<span class="alert alert-error">:message</span>') }}
			</div>
			
			<div class="form-group"  id="description">
			{{ Form::label('description', 'Description: ') }}
			{{ Form::input('textarea', 'description', null, ['class' => 'form-control style_2', 'required' => 'required'] ) }}
			{{ $errors->first('description', '<span class="alert alert-error">:message</span>') }}
			</div>	
			
			<div class="form-group"  id="directions">
			{{ Form::label('directions', 'Directions to Trail Head: ') }}
			{{ Form::input('textarea', 'directions', null, ['class' => 'form-control style_2'] ) }}
			{{ $errors->first('directions', '<span class="alert alert-error">:message</span>') }}
			</div>	
			
			<div class="form-group"  id="maintenance">
			{{ Form::label('maintenance', 'Planned Maintenance and Trail Events: ') }}
			{{ Form::input('textarea', 'maintenance', null, ['class' => 'form-control style_2'] ) }}
			{{ $errors->first('maintenance', '<span class="alert alert-error">:message</span>') }}
			</div>	
			
			<div class="form-group"  id="surface">
			{{ Form::label('surface', 'Trail Surface: ') }}
			{{ Form::input('textarea', 'surface', null, ['class' => 'form-control style_2'] ) }}
			{{ $errors->first('surface', '<span class="alert alert-error">:message</span>') }}
			</div>	
			
			<div class="form-group"  id="season">
			{{ Form::label('season', 'Trail Season: ') }}
			{{ Form::input('text', 'season', null, ['class' => 'form-control style_2'] ) }}
			{{ $errors->first('season', '<span class="alert alert-error">:message</span>') }}
			</div>	
			
			<div class="form-group"  id="hazards">
			{{ Form::label('hazards', 'Uncommon Hazards: ') }}
			{{ Form::input('textarea', 'hazards', null, ['class' => 'form-control style_2'] ) }}
			{{ $errors->first('hazards', '<span class="alert alert-error">:message</span>') }}
			</div>	
			
			<div class="form-group"  id="land_access">
			{{ Form::label('land_access', 'Land Access Issues: ') }}
			{{ Form::input('textarea', 'land_access', null, ['class' => 'form-control style_2'] ) }}
			{{ $errors->first('land_access', '<span class="alert alert-error">:message</span>') }}
			</div>	
			
			<a href="#" role="button" class="btn" data-toggle="modal" data-target="#imagemodal">Add an image</a>
			<div id="image_output" ></div>
			<div id="images">
			</div>
			
			<br/><br/>
			
			<a href="#" role="button" class="btn" data-toggle="modal" data-target="#pointmodal">Add a Point of Interest</a>
			<div id="point_output" ></div>
			<div id="points">
			</div>
			
			
		</fieldset>
		<br/><br/>
		<p class="submitButton">
		{{ Form::submit('Create Trail', ['class' => 'btn btn-default']) }}
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

@stop