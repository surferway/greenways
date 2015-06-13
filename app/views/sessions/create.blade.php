@extends('layouts.default')

@section('meta-title', 'Login');
@section('content')
    <h2>
        Log In
    </h2>
    <p>
		<a href="{{ URL::route('login.google') }}"><img src="{{ asset('/images/google-login-button.png') }}" alt="Sign in with Google"></a>
		<br/>
		<a href="{{ URL::route('login.facebook') }}"><img src="{{ asset('/images/facebook-login-button.png') }}" alt="Sign in with Facebook"></a>
		<br/>	
		<!--
        Please enter your username and password.
        <a id="MainContent_RegisterHyperLink" href="{{ URL::route('users.create') }}">Register</a> if you don't have an account.
		-->
    </p>
    
	<span class="failureNotification">
		
	</span>
	<div id="MainContent_LoginUser_LoginUserValidationSummary" class="failureNotification" style="display:none;">
	</div>
	@if (0 > 1)
		@if (Session::has('flash_message'))
			<div class="alert alert-warning">
				<p>{{ Session::get('flash_message') }}
			</div>
		@endif
		{{ Form::open(['route' => 'sessions.store']) }}
		<div class="accountInfo">
			<fieldset class="login">

				<legend>Account Information</legend>
				<p>
				{{ Form::label('username', 'Username: ') }}
				{{ Form::input('text', 'username', '', ['class' => 'textEntry'] ) }}
				{{ $errors->first('username', '<span class=error>:message</span>') }}
				</p>
				<p>
				{{ Form::label('password', 'Password: ') }}
				{{ Form::input('password', 'password', '', ['class' => 'passwordEntry'] ) }}
				{{ $errors->first('password', '<span class=error>:message</span>') }}
				</p>
				<p>
				{{ Form::checkbox('remember_me', true, true) }}
				{{ Form::label('remember_me', 'Keep me logged in', ['class' => 'inline'] ) }}
				</p>
			</fieldset>
			<p class="button_medium">
			{{ Form::submit('Log In') }}
			</p>
		</div>
		{{ Form::close() }}
	@endif
		
@stop