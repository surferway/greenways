<?php

class SessionsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		if (Auth::check())  Redirect::intended('/');
		return View::make('sessions.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
	
		if (Auth::attempt(Input::only('username', 'password'), Input::get('rememberme')))
		{
		    //return Input::get('rememberme');
			//return 'Welcome' . Auth::user()->username;
			//return Auth::user();
			return Redirect::intended('/search');
		
		}
		
		//return 'Failed';
		//return Redirect::back();
		//return Redirect::back()->withInputs(Input::only('username', 'password'));
		return Redirect::back()->withInputs(Input::all())->withFlashMessage('Invalid credentials provided');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id=null)
	{
		Auth::logout();
		Session::flush();
		
		//return dd(Auth::check());
		
		return Redirect::route('sessions.create');
	}
	
	public function loginWithForm() {
		if (Auth::check())  Redirect::intended('/');
		return View::make('sessions.login');
	}
	
	public function loginWithGoogle() {

		// get data from input
		$code = Input::get( 'code' );

		// get google service
		$googleService = OAuth::consumer( 'Google' );

		// check if code is valid
		
		//return "Code1 " . $code;

		// if code is provided get user data and sign in
		if ( !empty( $code ) ) {
		
			//return "Code2 " . $code;

			// This was a callback request from google, get the token
			$token = $googleService->requestAccessToken( $code );

			// Send a request with it
			$result = json_decode( $googleService->request( 'https://www.googleapis.com/oauth2/v1/userinfo' ), true );
			
			$message = 'Your unique Google user id is: ' . $result['id'] . ' and your name is ' . $result['name'] . " " .  $result['email'];
			//return $message;
			//Your unique Google user id is: 102095023603039254019 and your name is Philip Boyer surferway2@gmail.com
			
			$attempt = Auth::attempt([
				'email' => $result['email'],
				'password' => $result['id']
			]);
			
			
			
			if ($attempt){
			
			    //return $message . " Attempt yes" . $attempt ."<br/>";
			
				return Redirect::intended('/');
			} else {
			
				$name_input['username'] = $result['name'];
				$name_rules = array('username' => 'unique:users,username'); 
				$name_validator = Validator::make($name_input, $name_rules);
			
				$input['email'] = $result['email'];
				$rules = array('email' => 'unique:users,email');
				$validator = Validator::make($input, $rules);
				
				//return "Validator " . $validator->fails();

				if ($validator->fails()) {
				
					//return $message . " Attempt fail" . $attempt ."<br/>";
					$user = User::findByEmailOrFail($input['email']);
					$user->username = $result['name'];
					$user->password = Hash::make($result['id']);
					$user->save();
					
					//return $user->email . " " . $user->password ."<br/>";
					$attempt = Auth::attempt([
						'email' => $user->email,
						'password' => $user->password
					]);
					
					if ($attempt){
						return Redirect::intended('/');
					} else {
					
						return Redirect::guest('login/form')->withFlashMessage('There was a problem with your Google account');
					}
				} else {
				
					if ($name_validator->fails()) {
						return Redirect::guest('login/form')->withFlashMessage('Your username ' . $result['name'] . '  is already taken. Please contact <a href="mailto:info@greenways.ca">info@greenways.ca</a>');
					}
				
					$user = new User();
					$user->email = $result['email'];
					$user->username = $result['name'];
					$password = Hash::make($result['id']);
					$user->password = $password;
					$user->org_id = 1;		
					$user->save();
					
					$user->roles()->attach(3);	
					
					$attempt = Auth::attempt([
						'email' => $result['email'],
						'password' => $result['id']
					]);
					
					return Redirect::intended('/');
					
				}
				
			}

			//Var_dump
			//display whole array().
			//dd($result);

		}
		// if not ask for permission first
		else {
			// get googleService authorization
			$url = $googleService->getAuthorizationUri();

			// return to google login url
			return Redirect::to( (string)$url );
		}
	}

	/**
 * Login user with facebook
 *
 * @return void
 */

	public function loginWithFacebook() {

		// get data from input
		$code = Input::get( 'code' );

		// get fb service
		$fb = OAuth::consumer( 'Facebook' );

		// check if code is valid

		// if code is provided get user data and sign in
		if ( !empty( $code ) ) {

			// This was a callback request from facebook, get the token
			$token = $fb->requestAccessToken( $code );

			// Send a request with it
			$result = json_decode( $fb->request( '/me' ), true );

			$message = 'Your unique facebook user id is: ' . $result['id'] . ' and your name is ' . $result['name'];
			//echo $message. "<br/>";
			
				$attempt = Auth::attempt([
				'email' => $result['email'],
				'password' => $result['id']
			]);
			
			if ($attempt){
				return Redirect::intended('/');
			} else {
			
				$name_input['username'] = $result['name'];
				$name_rules = array('username' => 'unique:users,username'); 
				$name_validator = Validator::make($name_input, $name_rules);
			
				$input['email'] = $result['email'];
				$rules = array('email' => 'unique:users,email');
				$validator = Validator::make($input, $rules);

				if ($validator->fails()) {
				
					$user = User::findByEmailOrFail($input['email']);
					$user->username = $result['name'];
					$user->password = Hash::make($result['id']);
					$user->save();
					
					//return $user->email . " " . $user->password ."<br/>";
					$attempt = Auth::attempt([
						'email' => $user->email,
						'password' => $user->password
					]);
					
					if ($attempt){
						return Redirect::intended('/');
					} else {
					
						return Redirect::guest('login/form')->withFlashMessage('There was a problem with your Facebook account');
					}

				} else {
				
					if ($name_validator->fails()) {
						return Redirect::guest('login/form')->withFlashMessage('Your username ' . $result['name'] . '  is already taken. Please contact <a href="mailto:info@greenways.ca">info@greenways.ca</a>');
					}
				
					$user = new User();
					$user->email = $result['email'];
					$user->username = $result['name'];
					$password = Hash::make($result['id']);
					$user->password = $password;
					$user->org_id = 1;						
					$user->save();
					
					$user->roles()->attach(3);	
					
					$attempt = Auth::attempt([
						'email' => $result['email'],
						'password' => $result['id']
					]);
					
					return Redirect::intended('/');
					
				}
				
			}

			//Var_dump
			//display whole array().
			//dd($result);

		}
		// if not ask for permission first
		else {
			// get fb authorization
			$url = $fb->getAuthorizationUri();

			// return to facebook login url
			 return Redirect::to( (string)$url );
		}

	}
	
}
