@extends('layouts.default')

@section('content')
		<h2>All Users</h2>

		@if ($users->count())
			<div class="table-responsive">
			<table class="table table-striped table-hover">
			<thead>
			<tr>
				<th>Username</th>
				<th>Email</th>
				<th>Type</th>				
				<th>Delete</th>				
			</tr>
			<thead>
			<tbody>
			@foreach ($users as $user)
			<tr>
			<td>
				{{ link_to("/users/{$user->username}", $user->username) }}
			</td> 
			<td>
			{{ $user->email }}
			</td>
			<td>
			@foreach ( $user->roles as $role )
				{{ $role->name }}
			@endforeach
			</td>
			<td>
			{{ Form::open(array('method' => 'DELETE', 'route' => array('users.destroy', $user->id))) }}                       
            {{ Form::submit('Delete', array('class'=> 'btn btn-danger', 'data-confirm' => 'Are you sure you want to delete this user?')) }}	
			{{ Form::close() }}
			</td>
			</tr>
			@endforeach
			</tbody>
		</table>
		</div>	
		@else
			<p>Unfortunately, there are no users </p>
		@endif
@stop

