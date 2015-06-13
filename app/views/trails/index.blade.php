@extends('layouts.default')

@section('content')
		<h2>My Trails</h2>
	@if ($trails->count())
	<div class="table-responsive">
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th>Trail Name</th>
				<th>Location</th>
				<th>Rating</th>	
				<th>Level</th>	
				<th>Length</th>					
			</tr>
		<thead>
		<tbody>
		@foreach ($trails as $trail)
			<tr>
			<td>{{ link_to("/trails/{$trail->id}", $trail->name) }}   
			@if ($trail->active)
				<img src="{{ asset('images/approved.png') }}" />
			@endif
			
			@if ($trail->organization)
				<br>({{ $trail->organization->organization }})
			</td>
			@endif
			<td>
				{{ $trail->location }} 

			</td>
			<td>
			@for ($i=1; $i<=$trail->rating; $i++)
				<span class="glyphicon glyphicon-star"></span>
			@endfor	
			</td>
			<td>
				{{ $trail->difficulty }}
			</td>	
			<td>
				{{ number_format((float)$trail->distance, 2) }} km
			</td>	
			<td>
				{{ link_to("kml/{$trail->kml_name}", "GPS") }}
			</td>	
			<td>
			{{ link_to_route('trails.edit', 'Edit', array($trail->id), array('class' => 'btn btn-info')) }}
            <td>	
			<td>
			{{ Form::open(array('method' => 'DELETE', 'route' => array('trails.destroy', $trail->id))) }}                       
            {{ Form::submit('Delete', array('class'=> 'btn btn-danger', 'data-confirm' => 'Are you sure you want to delete this trail?')) }}	
			{{ Form::close() }}
			</td>
			</tr>
		@endforeach
		</tbody>
	</table>
	</div>
	@else
		<p>Unfortunately, you have no trails </p>
	@endif
@stop