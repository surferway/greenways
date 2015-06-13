<?php

class UsersController extends \BaseController {

	protected $user;
	
	public function __construct(User $user)
	{
		$this->user = $user;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
	
		if (Auth::check()){
			if (Auth::user()->hasRole('administrator')){
				$users = $this->user->all();
			//} else {
			//	$users = $this->user->where('user_id', Auth::user()->id)->get();
			}
			return View::make('users.index', ['users' => $users]);
		}
		return Redirect::guest('login')->withFlashMessage('You must be a logged in as Administrator to view users');
		
		
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		if (Auth::check()) return Redirect::to('search');
		return View::make('users.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
	
		$input = Input::all();
		$input['password'] = Hash::make($input['password']);
		if ( ! $this->user->fill($input)->isValid() )
		{
			return Redirect::back()->withInput()->withErrors($this->user->errors);
		}
		
		//$user = new User;
		//$user->username = Input::get('username');
		//$user->password = Hash::make(Input::get('password'));
		//$user->save();
		
		$this->user->org_id = 1;
		
		
		$this->user->save();
		
		$this->user->roles()->attach(3);
		
		if (Auth::attempt(Input::only('username', 'password'), Input::get('rememberme')))
		{
		    //return Input::get('rememberme');
			//return 'Welcome' . Auth::user()->username;
			//return Auth::user();
			return Redirect::intended('/search');
		}
		
		return Redirect::route('login');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($username)
	{
		$user = $this->user->whereUsername($username)->first();
	
		return View::make('users/show', ['user' => $user]);
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
	public function destroy($id)
	{
		User::find($id)->delete();
        return Redirect::route('users.index');
	}


}
