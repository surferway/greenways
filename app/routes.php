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

Route::get('login/google', ['as' => 'login.google', 'uses' => 'SessionsController@loginWithGoogle']);
Route::post('login/google', 'SessionsController@loginWithGoogle');

Route::get('login/facebook', ['as' => 'login.facebook', 'uses' => 'SessionsController@loginWithFacebook']);
Route::post('login/facebook', 'SessionsController@loginWithFacebook');

Route::get('login/form', ['as' => 'login.form', 'uses' => 'SessionsController@loginWithForm']);

Route::resource('users', 'UsersController');

Route::resource('sessions', 'SessionsController');

Route::resource('trails', 'TrailsController');

Route::get('admin', function()
{
	return Redirect::route('login');
})->before('auth');

// Default to search
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

Route::post('trails/pointupload', ['as' => 'trails.pointupload', 'uses' => 'TrailsController@pointUpload']);

Route::post('trails/reviewupload', ['as' => 'trails.reviewupload', 'uses' => 'TrailsController@reviewUpload']);

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
	
	// if ($trails->count()){
		// foreach($trails as $trail){
	
			// $marker = new Marker;
			// $marker->name = $trail->name;
			// $marker->lat = $trail->start_lat;
			// $marker->lng= $trail->start_lng;	
			
			// $markers[] = $marker;
		// }
	// }

	// JavaScript::put(['markers' => $markers]);
	
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

//Route::get('testPDF', 'PrintController@testPDF');
Route::get('print/testpdf', ['as' => 'print.testpdf', 'uses' => 'PrintController@testPDF']);
Route::get('print/trailpdf', ['as' => 'print.trailpdf', 'uses' => 'PrintController@trailPDF']);

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


Route::group(['prefix' => 'api/v1'], function(){



	Route::get('GetALL', function(){
		//return Response::json(Trail::all()->with('organization', 'activity', 'amenity'));
		return Response::json(Trail::where('active', 1)->with('user', 'organization', 'activity', 'amenity')->get());
	});
	
	Route::get('GetAll', function(){
		//return Response::json(Trail::all()->with('organization', 'activity', 'amenity'));
		return Response::json(Trail::where('active', 1)->with('user', 'organization', 'activity', 'amenity')->get());
	});
	
	Route::get('GetChanges/{date}', function($date){
	
		if (strlen($date) > 3){
			$updated_at = date('Y-m-d', strtotime($date));
		} else {
			$lastweek = strtotime("-7 day");
			$updated_at = date("Y-m-d", $lastweek);
		}
		
		//return $updated_at;
		//return Response::json(Trail::all()->with('organization', 'activity', 'amenity'));
		return Response::json(Trail::where('updated_at', '>=', $updated_at)->where('active', 1)->with('user', 'organization', 'activity', 'amenity')->get());
	});
	
	Route::get('GetChanges/', function(){
	
		$lastweek = strtotime("-7 day");
		$updated_at = date("Y-m-d", $lastweek);
		
		//return $updated_at;
		//return Response::json(Trail::all()->with('organization', 'activity', 'amenity'));
		return Response::json(Trail::where('updated_at', '>=', $updated_at)->where('active', 1)->with('user', 'organization', 'activity', 'amenity')->get());
	});
	
	Route::get('GetComments/', function(){
		return Response::json(Review::where('approved', 1)->with('user')->get());
	});
	
	Route::get('GetComments/{date}', function($date){
		if (strlen($date) > 3){
			$updated_at = date('Y-m-d', strtotime($date));
		} else {
			$lastweek = strtotime("-7 day");
			$updated_at = date("Y-m-d", $lastweek);
		}
		return Response::json(Review::where('updated_at', '>=', $updated_at)->where('approved', 1)->with('user')->get());
	});
	
/* 	Route::get('GetUserComments/{userid}/', function($userid){
		if (!($userid > 0)){
			$userid = 1;
		}
		return Response::json(Review::where('approved', 1)->where('user_id', $userid)->get());
	});
	
	Route::get('GetUserComments/{userid}/{date}/', function($userid, $date){
		if (!($userid > 0)){
			$userid = 1;
		}
		if (strlen($date) > 3){
			$updated_at = date('Y-m-d', strtotime($date));
		} else {
			$lastweek = strtotime("-7 day");
			$updated_at = date("Y-m-d", $lastweek);
		}
		return Response::json(Review::where('updated_at', '>=', $updated_at)->where('approved', 1)->where('user_id', $userid)->get());
	}); */
	
	Route::get('GetUserComments/{username}/', function($username){
	
		$user = User::findByUsernameOrFail($username);
		//return $user->id;
		return Response::json(Review::where('approved', 1)->where('user_id', $user->id)->with('user')->get());
	});
	
	Route::get('GetUserComments/{username}/{date}/', function($username, $date){
	
		$user = User::findByUsernameOrFail($username);

		if (strlen($date) > 3){
			$updated_at = date('Y-m-d', strtotime($date));
		} else {
			$lastweek = strtotime("-7 day");
			$updated_at = date("Y-m-d", $lastweek);
		}
		return Response::json(Review::where('updated_at', '>=', $updated_at)->where('approved', 1)->where('user_id', $user->id)->with('user')->get());
	});
	
	Route::get('GetUserComments/', function(){
		return Response::json(Review::where('approved', 1)->with('user')->get());
	});
	
	Route::get('GetTrailComments/{trailid}', function($trailid){
		return Response::json(Review::where('approved', 1)->where('trail_id', $trailid)->with('user')->get());
	});
	
	Route::get('GetTrailComments/{trailid}/{date}/', function($trailid, $date){
		if (strlen($date) > 3){
			$updated_at = date('Y-m-d', strtotime($date));
		} else {
			$lastweek = strtotime("-7 day");
			$updated_at = date("Y-m-d", $lastweek);
		}
		return Response::json(Review::where('updated_at', '>=', $updated_at)->where('approved', 1)->where('trail_id', $trailid)->with('user')->get());
	});
	
	Route::get('GetTrailComments/', function(){
		return Response::json(Review::where('approved', 1)->with('user')->get());
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

		$difficulties = Difficulty::orderBy('id')->get();	
		$activities = Activity::orderBy('id')->get();	
		$trails = Trail::orderBy('name')->where('active', 1)->get();
		$activity_image_id = 0;
			
		return View::make('layouts.partials.trails_table', ['trails' => $trails, 'difficulties' => $difficulties, 'activities' => $activities, 'activity_image_id' => $activity_image_id]);

});

Route::post('trails_table', function(){

		$trail_ids = "";
		$difficulties = Difficulty::orderBy('id')->get();	
		
		$activities = Activity::orderBy('id')->get();	
		
		$trails_array = array();
		$intersected_trails = array();

		$activities_checked = Input::get('activities');
		$difficulties_checked = Input::get('difficulties');	
		
		$duration = Input::get('duration');	
		$duration_array = array(); 
		//below 2 lines for single max range slider
		$duration_array[0] = 0;
		$duration_array[1] = $duration;
		//below used for range slider
		//$duration_array = explode(",", $duration);		
		
		$distance = Input::get('distance');	
		$distance_array = array(); 
		//below 2 lines for single max range slider
		$distance_array[0] = 0;
		$distance_array[1] = $distance;
		//below used for range slider
		//$distance_array = explode(",", $distance);
		
		$activity_image_id = 0;
		if (strlen($activities_checked) > 0){
			$activities_checked_array = array();
			$activities_checked_array = explode(",", $activities_checked);
			$activity_image_id = $activities_checked_array[0];
		}
		
		if (strlen($difficulties_checked) > 0){
			$difficulties_checked_array = array();
			$difficulties_checked_array = explode(",", $difficulties_checked);
		}
		
		$activetrails = Trail::where('active', 1)->get();
		
		foreach ($activetrails as $trail){
		
			if ((intval(substr($trail->duration, 0, 1))) > 0){
				$duration1 = intval(substr($trail->duration, 0, 1));
			} else {
				$duration1 = 0;
			}
			if ((intval(substr($trail->duration, 2, 1))) > 0){
				$duration2 = intval(substr($trail->duration, 2, 1));
			} else {
				$duration2 = 9;
			}
			
			if (strlen($activities_checked) > 0){
				foreach ($trail->activity as $activity){
					foreach ($activities_checked_array as $activity_id){
						if ($activity['id'] == $activity_id){
								//$activities_array[$activity_id] .= "," . $trail->id;
								$trails_array[$activity['name']][] = $trail->id;
						}
					}
				}
			}
			if (strlen($difficulties_checked) > 0){
				if (in_array($trail->difficulty, $difficulties_checked_array)){
					$trails_array['difficulty'][] = $trail->id;
				}
			}
			//if ((($trail->distance >= $distance_array[0]) and ($trail->distance <= $distance_array[1]))  || (($distance_array[0] == 0) and ($distance_array[1] == 100))){
			if (count($distance_array) > 1){
				if ((($trail->distance >= $distance_array[0]) and (($trail->distance <= $distance_array[1]) || ($distance_array[1] == 40))) || (($distance_array[0] == 0) and ($distance_array[1] == 40))){
					$trails_array['distance'][] = $trail->id;
				}
			}
			//if (((intval($trail->duration) >= $duration_array[0]) and (intval($trail->duration) <= $duration_array[1]))  || (($duration_array[0] == 0) and ($duration_array[1] == 8))){
			if (count($duration_array) > 1){
				if ((($duration1 >= $duration_array[0]) and (($duration1) <= $duration_array[1]))  || (($duration2 >= $duration_array[0]) and (($duration2) <= $duration_array[1]))){
					$trails_array['duration'][] = $trail->id;
				}
			}
			
		}
		
		//return dd($activities);
 		if (strlen($activities_checked) > 0){
			foreach ($activities_checked_array as $activity_id){
				foreach ($activities as $active){
					if ($active['id'] == $activity_id){
						if (!isset($trails_array[$active['name']])){
							$trails_array[$active['name']][] = 0;
						}
					}
				}
			}
		}
		
		if ((strlen($difficulties_checked) > 0) and (!(isset($trails_array['difficulty'])))) {
				$trails_array['difficulty'][] = 0;
		}
		
		if (($distance_array[0] > 0) || ($distance_array[0] < 40) and (!(count($trails_array) > 1))){
			$trails_array['distance'][] = 0;
		}
		
		if (count($trails_array) > 1){
			$intersected_trails = call_user_func_array('array_intersect',$trails_array);
		} else if (count($trails_array) == 1) {
			$intersected_trails = array_pop($trails_array);
		}
		
		if (count($intersected_trails) > 0){
			$trails = Trail::orderBy('name')->whereIn('id', $intersected_trails)->get();
		} else {
			$trails = new \Illuminate\Database\Eloquent\Collection;
		}
		
		//return dd($trails_array);
		return View::make('layouts.partials.trails_table', ['trails' => $trails, 'difficulties' => $difficulties, 'activities' => $activities, 'activity_image_id' => $activity_image_id]);

});
