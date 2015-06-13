@extends('layouts.search')

@section('content')
<script type="text/javascript">

	
</script>	

	<h2>Trails Search</h2>
	
	<div class="row">
		<div class="col-md-5">
		
			{{ Form::open(['data-remote-success-message' => 'All Done','id' => 'searchForm']) }}
			
			<div class="form-group" id="activity_search">
			{{ Form::label('activities', 'Select Activity Type ') }}
			<br/>
			@if ($activities->count())
				@foreach ($activities as $activity)
					<label class="checkbox-inline" data-toggle="tooltip" data-placement="bottom" title="{{ $activity->name }}">
					@if (empty($activity_checked))
						{{ Form::checkbox('activities[]', $activity->id, null, ['class' => 'field', 'id' => $activity->name]) }}
					@else
						{{ Form::checkbox('activities[]', $activity->id, in_array($activity->id, $activity_checked), ['class' => 'field', 'id' => $activity->name]) }}					
					@endif
					<label for="{{ $activity->name }}"><img src="{{ asset($activity->button) }}" ></label>
					</label>
				@endforeach
			@endif
			</div>	
			
			<div class="form-group"  id="difficulty_search">
			{{ Form::label('difficulty', 'Select Difficulty') }}
			<br/>
			@if ($difficulties->count())
				@foreach ($difficulties as $difficulty)
					<label class="checkbox-inline" data-toggle="tooltip" data-placement="bottom" title="{{ $difficulty->name }}">
					{{ Form::checkbox('difficulty', $difficulty->name, null, ['class' => 'field', 'id' => $difficulty->name]) }}
					<label for="{{ $difficulty->name }}"><img src="{{ asset($difficulty->icon) }}"></label>
					</label>
				@endforeach
			@endif
			</div>	
			
			<div class="form-group"  id="duration_search">
			{{ Form::label('duration', 'Select Duration') }}
			<br/>
			0 hours &nbsp; &nbsp; &nbsp; 
			<!--
			Range slider 0 to 8
			<input id="duration-slider" type="text" class="span2" value="" data-slider-min="0" data-slider-max="8" data-slider-step="1" data-slider-value="[0,8]"/>
			
			Single value slider assumed minimum of 0
			-->
			<input id="duration-slider" type="text" class="span2" data-slider-id='duration-sliderSlider' data-slider-min="0" data-slider-max="8" data-slider-step="1" data-slider-value="8"/>
			&nbsp; &nbsp; &nbsp; 8 hours
			</div>	
			<br/>
			
			<div class="form-group"  id="distance_search">
			{{ Form::label('distance', 'Select Distance') }}
			<br/>
			0 km &nbsp; &nbsp; &nbsp; 
			<!-- 
			Range slider 0 to 40
			<input id="distance-slider" type="text" class="span2" value="" data-slider-min="0" data-slider-max="40" data-slider-step="1" data-slider-value="[0,40]"/>
			
			Single value slider assumed minimum of 0
			-->
			<input id="distance-slider" type="text" class="span2" data-slider-id='distance-sliderSlider' data-slider-min="0" data-slider-max="40" data-slider-step="1" data-slider-value="40"/>
			&nbsp; &nbsp; &nbsp; 40km
			</div>	

			<br>
			 <input id="reset" value="Reset" type="button">

			<!--
			<div class="form-group">
			{{ Form::submit('Find Your Trail', ['class' => 'btn btn-default']) }}
			</div>
			-->
				

			{{ Form::close() }}
			
			
			<br/>
			<br/>

			<!-- Trails Table -->
			<div id="trails_table">
			
			</div>
			<!-- Trails Table -->			
			
		</div>
		<div class="col-md-7">
		<div id="map-area"></div>	
		</div>
	</div>	
		


@stop