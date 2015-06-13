@extends('layouts.default')

@section('content')

           
    <h2>
    Create a New Account
    </h2>
	<p>
		Use the form below to create a new account.
	</p>
	<p>
		Passwords are required to be a minimum of 6 characters in length.
	</p>
	<span class="failureNotification">
		
	</span>
	<div id="MainContent_RegisterUser_CreateUserStepContainer_RegisterUserValidationSummary" class="failureNotification" style="display:none;">
	</div>
	
	{{ Form::open(['route' => 'users.store']) }}
	<div class="accountInfo">
		<fieldset class="register">
			@if (Session::has('flash_message'))
				<div class="alert alert-warning">
					<p>{{ Session::get('flash_message') }}
				</div
			@endif		
			<legend>Account Information</legend>
			<p>
			{{ Form::label('username', 'Username: ') }}
			{{ Form::input('text', 'username', '', ['class' => 'textEntry'] ) }}
			{{ $errors->first('username', '<span class=error>:message</span>') }}
			</p>
			<p>
			{{ Form::label('email', 'Email: ') }}
			{{ Form::input('text', 'email', '', ['class' => 'textEntry'] ) }}
			{{ $errors->first('email', '<span class=error>:message</span>') }}
			</p>			
			<p>
			{{ Form::label('password', 'Password: ') }}
			{{ Form::input('password', 'password', '', ['class' => 'passwordEntry'] ) }}
			{{ $errors->first('password', '<span class=error>:message</span>') }}
			</p>
			<p>
			{{ Form::label('password_confirmation', 'Confirm Password: ') }}
			{{ Form::input('password', 'password_confirmation', '', ['class' => 'passwordEntry'] ) }}
			{{ $errors->first('password_confirmation', '<span class=error>:message</span>') }}
			</p>
		</fieldset>
		<p class="submitButton">
		{{ Form::submit('Create User') }}
		</p>
	</div>
	{{ Form::close() }}
	

		
	
		
@stop