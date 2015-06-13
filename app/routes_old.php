<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

//Route::get('users', 'UsersController@index');
//Route::get('users/{username}', 'UsersController@show');

//Route::get('login', 'SessionsController@create');
//Route::get('logout', 'SessionsController@destroy');

Route::get('login', ['as' => 'login', 'uses' => 'SessionsController@create']);
Route::get('logout', ['as' => 'logout', 'uses' => 'SessionsController@destroy']);

Route::resource('users', 'UsersController');

Route::resource('sessions', 'SessionsController');

Route::resource('trails', 'TrailsController');

Route::get('admin', function()
{
	return Redirect::route('login');
})->before('auth');

Route::get('/', function()
{
	$markers = array();

	class Marker {
       public $name = "";
       public $lat  = "";
       public $lng = "";
	   public $level = "";
	}

	//$trails = Trail::all();
	$trails = Trail::where('active', 1)->get();
	
	if ($trails->count()){
		foreach($trails as $trail){
			$marker = new Marker;
			$marker->name = $trail->name;
			$marker->lat = $trail->start_lat;
			$marker->lng= $trail->start_lng;
			$marker->level= $trail->difficulty;				
			
			$markers[] = $marker;
		}
	}

	JavaScript::put(['markers' => $markers]);

	$activities = Activity::all();
	$amenities = Amenity::orderBy('name')->get();
	$difficulties = Difficulty::orderBy('id')->get();	
	return View::make('search', ['trails' => $trails, 'activities' => $activities, 'amenities' => $amenities, 'difficulties' => $difficulties]);
	
});

Route::post('trails/imageupload', ['as' => 'trails.imageupload', 'uses' => 'TrailsController@imageUpload']);

Route::get('search/', function(){

	$markers = array();

	class Marker {
       public $name = "";
       public $lat  = "";
       public $lng = "";
	   public $level = "";
	}

	//$trails = Trail::all();
	$trails = Trail::where('active', 1)->get();
	
	if ($trails->count()){
		foreach($trails as $trail){
			$marker = new Marker;
			$marker->name = $trail->name;
			$marker->lat = $trail->start_lat;
			$marker->lng= $trail->start_lng;
			$marker->level= $trail->difficulty;				
			
			$markers[] = $marker;
		}
	}

	JavaScript::put(['markers' => $markers]);

	$activities = Activity::all();
	$amenities = Amenity::orderBy('name')->get();
	$difficulties = Difficulty::orderBy('id')->get();	
	return View::make('search', ['trails' => $trails, 'activities' => $activities, 'amenities' => $amenities, 'difficulties' => $difficulties]);
});

Route::post('search/', function(){

	$activity_checked = Input::get('activities');

	$markers = array();

	class Marker {
       public $name = "";
       public $lat  = "";
       public $lng = "";
	}
	//$trails = Trail::all();
	$trails = Trail::where('active', 1)->get();
	
	if ($trails->count()){
		foreach($trails as $trail){
	
			$marker = new Marker;
			$marker->name = $trail->name;
			$marker->lat = $trail->start_lat;
			$marker->lng= $trail->start_lng;	
			
			$markers[] = $marker;
		}
	}

	JavaScript::put(['markers' => $markers]);
	
	$rating = Input::get('rating');
	
	//JavaScript::put(['name' => $rating]);
	
	$activities = Activity::all();
	$amenities = Amenity::orderBy('name')->get();
	$difficulties = Difficulty::orderBy('id')->get();

	return View::make('search', ['trails' => $trails, 'activities' => $activities, 'activity_checked' => $activity_checked, 'amenities' => $amenities, 'difficulties' => $difficulties]);
	
	//return Response::json($trails);
});

Route::get('example/', function(){

	//$member = Role::create(['name' => 'member']);
	//$admin = Role::create(['name' => 'administrator']);
	//$owner = Role::create(['name' => 'owner']);
	
	//User::first()->roles()->attach(3);
	
	$role = Role::whereName('owner')->first();
	
	//return $role;
	
	$user = User::first();
	
	$trail = Trail::with('activity')->with('organization')->first();
	
	//if ($user->hasRole($role->name)) return 'You are the owner';
	//return 'You are not the owner';

	//return User::with('roles')->with('organization')->first();
	
	//return Organization::all();
	
	return $trail;

});

Route::get('about', 'PagesController@about');

//Auth::loginUsingId(1);

Route::get('reporting', function(){

	
	return 'Reporting area';
})->before('role::owner');

Route::get('test/', function(){

	//$kml_content = file_get_contents('http://trails.greenways.ca/kml/Mitchell%20Ridge.kml');
	
	//$elevation_json = file_get_contents('http://maps.googleapis.com/maps/api/elevation/json?locations=50.513599,-116.059984');
	
	//$xml = new XMLReader;
	
	//return $elevation_json;
	
	$trail = Trail::whereName('Marvel')->first();
		//$trail = Trail::findOrFail($id);

	return View::make('test', ['trail' => $trail]);
	
});

Route::get('activities', function(){

	return Activity::all();
});

Route::get('asearch/', function(){

	$activities = Activity::all();
	$amenities = Amenity::orderBy('name')->get();
	$difficulties = Difficulty::orderBy('id')->get();	
	return View::make('asearch', ['activities' => $activities, 'amenities' => $amenities, 'difficulties' => $difficulties]);
});

Route::group(['prefix' => 'api/v1'], function(){

	Route::get('alltrails', function(){
		//return Response::json(Trail::all()->with('organization', 'activity', 'amenity'));
		return Response::json(Trail::where('active', 1)->with('user', 'organization', 'activity', 'amenity')->get());
	});
	
	Route::get('activities', function(){
	
		$activities = Activity::all();
		 
		return Response::json([
			'activities' => $activities->toArray()
        ], 200);
		//return Response::json(Trail::all()->with('organization', 'activity', 'amenity'));
		//return Response::json(Activity::all()->toArray());
	});
	
	Route::get('amenities', function(){
	
		$amenities = Amenity::all();
		 
		return Response::json([
			'amenities' => $amenities->toArray()
        ], 200);

	});
	
	Route::resource('trails', 'ApiController');
	
});

Route::get('trails_table', function(){

		

		$activities_array = [ 5 ];
		$trails = Trail::where('active', 1)->get();
		
		// $trails = Trail::whereHas('activity', function($q) use ($activities_array)
		// {
			// $q->whereIn('activity_id', $activities_array);
		// })->get();
		
		
		//$trails = Trail::with('activity')->wherePivot('activity_trail.id','in',$activities_array)->get();
		
		// $filtered_trails = $trails->filter(function($trail)
		// {
			// 
			
				// if (in_array($trail->activity->activity_id, $activities_array)){
					// return $trail;
				// }

		// })->values();		
		
		return View::make('layouts.partials.trails_table', ['trails' => $trails]);

});

Route::post('trails_table', function(){

		$activities_array = array();

		$activities_checked = Input::get('activities');
		$difficulties_checked = Input::get('difficulties');	
		$duration = Input::get('duration');			
		$distance = Input::get('distance');	
		
		//return "HERE";
		
		if (strlen($activities_checked) > 0){
	
			//$trails = Trail::where('active', 1)->get();
			
			$activities_array = explode(",", $activities_checked);
		
			$trails = Trail::whereHas('activity', function($query) use ($activities_array)
			{
				$query->whereIn('activity_id', $activities_array);
			})->get();

		} else {
		
			$trails = Trail::where('active', 1)->get();
		}
		
		//$trails = Trail::where('active', 1)->get();
		
		//return $activities_checked . " -  " . $difficulties_checked . "  dur " . $duration . " dis " . $distance;
		
		//return dd(Input::all());
		
		return View::make('layouts.partials.trails_table', ['trails' => $trails]);

});
