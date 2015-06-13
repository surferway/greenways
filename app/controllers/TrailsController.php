<?php

class TrailsController extends \BaseController {

	protected $trail;
	
	public function __construct(Trail $trail)
	{
		$this->trail = $trail;
	}

	/**
	 * Display a listing of the trails.
	 *
	 * @return Response
	 */
	public function index()
	{
		if (Auth::check()){
			if (Auth::user()->hasRole('administrator')){
				$trails = Trail::all();
			} else {
				$trails = Trail::where('user_id', Auth::user()->id)->get();
			}
			return View::make('trails.index', ['trails' => $trails]);
		}
		return Redirect::guest('login')->withFlashMessage('You must be a logged in to manage Your Trails');
	}


	/**
	 * Show the form for creating a new trail.
	 
	 *
	 * @return Response
	 */
	public function create()
	{
	
		if (Auth::check()){
		
			$activities = Activity::all();
			$amenities = Amenity::orderBy('name')->get();
			$difficulties = Difficulty::orderBy('id')->get();		
			return View::make('trails.create', ['activities' => $activities, 'amenities' => $amenities, 'difficulties' => $difficulties]);
		}
		
		return Redirect::guest('login')->withFlashMessage('You must be logged in to Create a Trail');

	}


	/**
	 * Store a newly created trail.
	 *
	 * @return Response
	 */
	public function store()
	{
	
	if (Auth::check()){
	
		// create the validation rules ------------------------
		$rules = array(
			'name'    	=> 'required',
			'kml_name'	=> 'required',
			'difficulty'	=> 'required',
			'rating'	=> 'required'				
		);
		
		//'location'	=> 'required', 	
		

		// do the validation ----------------------------------
		// validate against the inputs from our form
		$validator = Validator::make(Input::all(), $rules);

		// check if the validator failed -----------------------
		if ($validator->fails()) {

			// get the error messages from the validator
			$messages = $validator->messages();

			// redirect our user back to the form with the errors from the validator
			return Redirect::to('trails/create')
				->withErrors($validator);

		} else {
		
			$activities_result = "";
			$amenities_result = "";		
			$trail = new Trail;
			$trail->fill(Input::all());
			$trail->name = Input::get('name');
			$trail->location = Input::get('location');
			$trail->org_id = Auth::user()->org_id;;	
			$trail->user_id = Auth::user()->id;	
			$trail->difficulty = Input::get('difficulty');
			$trail->rating = Input::get('rating');	
			
			if (Input::hasFile('kml_name'))
			{
			
				$file = Input::file('kml_name');
				$kml_name = $file->getClientOriginalName();
				$file = $file->move( public_path() . '/kml/', $kml_name);
				
				$kml_content = file_get_contents('http://trails.greenways.ca/kml/' . rawurlencode($kml_name));
				
				//parse kml file
				if (strlen($kml_content) > 0){
					$trail->kml_content = $kml_content;
					$xml = new XMLReader();
					$xml->xml($kml_content);
					
					$count = 0;
					$total_length = 0;
					$total_distance = 0;
					$prev_lat =0;
					$prev_lng = 0;
					$linestring = 0;
					$multitrack = 0;
					$path = array();
					$locations = "";
					$first = true;
					$firsttrack = true;
					$start_lat = 0.0;
					$start_lng = 0.0; 
					$end_lat = 0.0;
					$end_lng = 0.0; 
					while($xml->read()){
						$count++;
						
						if (($xml->nodeType == XMLREADER::ELEMENT)  && ($xml->localName == 'MultiTrack')){
							$multitrack = 1;    
						}
						if (($xml->nodeType == XMLREADER::ELEMENT)  && ($xml->localName == 'coord') && $multitrack ){
						   $xml->read();
							$pieces = explode(" ", $xml->value);   
							$member = array();
							$member['lat'] = $pieces[1];
							$member['lng'] = $pieces[0]; 
							
							$path[] = $pieces[1] . "," . $pieces[0];
							if ($first){
								$start_lat = $pieces[1];
								$start_lng = $pieces[0]; 
								$first = false;
							} else {
								$distance = distance($pieces[1],$pieces[0], $prev_lat, $prev_lng, "K");
								if (!(is_nan($distance))){
									$total_distance += $distance;
								}
								$end_lat = $pieces[1];
								$end_lng = $pieces[0]; 
							}
							$prev_lat = $pieces[1];
							$prev_lng = $pieces[0];       
						}
						
						if (($xml->nodeType == XMLREADER::ELEMENT)  && ($xml->localName == 'LineString')){
							$linestring = 1;    
						}
						if (($xml->nodeType == XMLREADER::END_ELEMENT)  && ($xml->localName == 'LineString')){
							$linestring = 0;   
							$firsttrack = false;
						}
						
						if (($xml->nodeType == XMLREADER::ELEMENT)  && ($xml->localName == 'coordinates')  && $linestring  && $firsttrack){
						   $xml->read();
							$parts = explode(" ", trim($xml->value)); 
							foreach($parts as $part){
								if (strpos($part, ",")){
									$pieces = explode(",",$part);
									$member = array();
									$member['lat'] = $pieces[1];
									$member['lng'] = $pieces[0];  
									$path[] = $pieces[1] . "," . $pieces[0];
									
									if ($first){
										$start_lat = $pieces[1];
										$start_lng = $pieces[0]; 
										$first = false;
									} else {
										$distance = distance($pieces[1],$pieces[0], $prev_lat, $prev_lng, "K");
										if (!(is_nan($distance))){
											$total_distance += $distance;
										} 
										$end_lat = $pieces[1];
										$end_lng = $pieces[0]; 									
									}
									$prev_lat = $pieces[1];
									$prev_lng = $pieces[0];
								}
							}
						}	
					}
					$startPos = $path[0];
					$endPos = $path[count($path) - 1];
					if(count($path) > 100){
					  while(count($path) > 200){
						$i = count($path) - 1;
						for (count($path) - 1; $i > 0; $i = $i-2) {
							array_splice($path, $i ,1);
						}
					  }
					}
					$path[count($path)-1] = $endPos;
					$kPath = json_encode($path);

					$locations = "";
					foreach ($path as $point){
						$locations .= $point . "|";
					}
					$locations = trim($locations, "|");		
					
					$trail->distance = $total_distance;
					$trail->start_lat = $start_lat;
					$trail->start_lng = $start_lng;
					$trail->end_lat = $end_lat;
					$trail->end_lng = $end_lng;				
					
				} // end kml content
				
				$trail->kml_name = $kml_name; //$file->getRealPath();
			}
			
			if ($trail->save()){
			
				$activities_checked = Input::get('activities');
				if(is_array($activities_checked))
				{
					$trail->activity()->sync($activities_checked);
				}
				
				$amenities_checked = Input::get('amenities');
				if(is_array($amenities_checked))
				{
					$trail->amenity()->sync($amenities_checked);
				}
				
				$image_ids = Input::get('imageids');
				if(is_array($image_ids))
				{
					$trail->image()->sync($image_ids);
				}
				
				$point_ids = Input::get('pointids');
				if(is_array($point_ids))
				{
					$trail->point()->sync($point_ids);
				}
				
			}
			
		}
	
	} else { // end Auth::check()	
	
		return Redirect::back();
		//return "Authorization to create Trail failed";
	
	}
		
		return Redirect::route('trails.index');
		

	}


	/**
	 * Display the specified trail.
	 *
	 * @param  int  $id
	 * @return Response
	 */
/* 	public function show($trailname)
	{
		$trail = $this->trail->whereName($trailname)->firstOrFail();
		
		return View::make('trails/show', ['trail' => $trail]);
	} */
	
	public function show($id)
	{
		$trail = $this->trail->findOrFail($id);
		
		// Get all reviews that are not spam for the trail and paginate them
		$reviews = $trail->reviews()->with('user')->approved()->notSpam()->orderBy('created_at','desc')->paginate(100);

		return View::make('trails/show', ['trail' => $trail, 'reviews'=>$reviews]);
	}


	/**
	 * Show the form for editing the trail.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
	
		if (Auth::check()){
			//$trail = Trail::find($id);
			$trail = Trail::findOrFail($id);
			
			$activities = Activity::all();	
			$amenities = Amenity::orderBy('name')->get();
			$difficulties = Difficulty::orderBy('id')->get();	
			
			$activity_checked = array();
			$activities_checked = $trail->activity;
			foreach ($activities_checked as $activity){
					$activity_checked[] = $activity->id;
			}
			
			$amenities_checked = $trail->amenity;
			$amenity_checked = array();
			foreach ($amenities_checked as $amenity){
					$amenity_checked[] = $amenity->id;
			}

			$ratings_array = get_ratings();

			return View::make('trails.edit', ['trail' => $trail, 'activities' => $activities, 'activity_checked' => $activity_checked, 'amenities' => $amenities, 'amenity_checked' => $amenity_checked, 'difficulties' => $difficulties, 'ratings_array' => $ratings_array]);
		}
		
		return Redirect::route('login')->withFlashMessage('You must be logged in to Edit a Trail');

	}


	/**
	 * Update the specified trail.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$trail = Trail::findOrFail($id);
		$trail->fill(Input::all());
		$trail->name = Input::get('name');
		$trail->location = Input::get('location');
		$trail->org_id = Auth::user()->org_id;;	
		$trail->user_id = Auth::user()->id;	
		$trail->difficulty = Input::get('difficulty');
		$trail->rating = Input::get('rating');	
		$trail->active = Input::get('active');
		$trail->distance = Input::get('distance');
		
			if (Input::hasFile('kml_name'))
			{
				$file = Input::file('kml_name');
				$kml_name = $file->getClientOriginalName();
				$file = $file->move( public_path() . '/kml/', $kml_name);
				
				$kml_content = file_get_contents('http://trails.greenways.ca/kml/' . rawurlencode($kml_name));
				
				
				//parse kml file
				if (strlen($kml_content) > 0){
					$trail->kml_content = $kml_content;
					$xml = new XMLReader();
					$xml->xml($kml_content);
					
					
					$count = 0;
					$total_length = 0;
					$total_distance = 0;
					$prev_lat =0;
					$prev_lng = 0;
					$linestring = 0;
					$multitrack = 0;
					$path = array();
					$locations = "";
					$first = true;
					$firsttrack = true;
					$start_lat = 0.0;
					$start_lng = 0.0; 
					$end_lat = 0.0;
					$end_lng = 0.0; 
					while($xml->read()){
						$count++;
						
						if (($xml->nodeType == XMLREADER::ELEMENT)  && ($xml->localName == 'MultiTrack')){
							$multitrack = 1;    
						}
						if (($xml->nodeType == XMLREADER::ELEMENT)  && ($xml->localName == 'coord') && $multitrack ){
						   $xml->read();
							$pieces = explode(" ", $xml->value);   
							$member = array();
							$member['lat'] = $pieces[1];
							$member['lng'] = $pieces[0]; 
							
							$path[] = $pieces[1] . "," . $pieces[0];
							if ($first){
								$start_lat = $pieces[1];
								$start_lng = $pieces[0]; 
								$first = false;
							} else {
								$distance = distance($pieces[1],$pieces[0], $prev_lat, $prev_lng, "K");
								if (!(is_nan($distance))){
									$total_distance += $distance;
								}
								$end_lat = $pieces[1];
								$end_lng = $pieces[0]; 
							}
							$prev_lat = $pieces[1];
							$prev_lng = $pieces[0];       
						}
						
						if (($xml->nodeType == XMLREADER::ELEMENT)  && ($xml->localName == 'LineString')){
							$linestring = 1;    
						}
						if (($xml->nodeType == XMLREADER::END_ELEMENT)  && ($xml->localName == 'LineString')){
							$linestring = 0;   
							$firsttrack = false;
						}
						
						if (($xml->nodeType == XMLREADER::ELEMENT)  && ($xml->localName == 'coordinates')  && $linestring  && $firsttrack){
						   $xml->read();
							$parts = explode(" ", trim($xml->value)); 
							foreach($parts as $part){
								if (strpos($part, ",")){
									$pieces = explode(",",$part);
									$member = array();
									$member['lat'] = $pieces[1];
									$member['lng'] = $pieces[0];  
									$path[] = $pieces[1] . "," . $pieces[0];
									
									if ($first){
										$start_lat = $pieces[1];
										$start_lng = $pieces[0]; 
										$first = false;
									} else {
										$distance = distance($pieces[1],$pieces[0], $prev_lat, $prev_lng, "K");
										if (!(is_nan($distance))){
											$total_distance += $distance;
										} 
										$end_lat = $pieces[1];
										$end_lng = $pieces[0]; 									
									}
									$prev_lat = $pieces[1];
									$prev_lng = $pieces[0];
								}
							}
						}	
					}
					$startPos = $path[0];
					$endPos = $path[count($path) - 1];
					if(count($path) > 100){
					  while(count($path) > 200){
						$i = count($path) - 1;
						for (count($path) - 1; $i > 0; $i = $i-2) {
							array_splice($path, $i ,1);
						}
					  }
					}
					$path[count($path)-1] = $endPos;
					$kPath = json_encode($path);

					$locations = "";
					foreach ($path as $point){
						$locations .= $point . "|";
					}
					$locations = trim($locations, "|");		
					
					if (!(strlen($trail->distance) > 0)){
						$trail->distance = $total_distance;
					}
					$trail->start_lat = $start_lat;
					$trail->start_lng = $start_lng;
					$trail->end_lat = $end_lat;
					$trail->end_lng = $end_lng;				
					
				} // end kml content
				
				$trail->kml_name = $kml_name; //$file->getRealPath();
			} else {
				$trail->kml_name = Input::get('prev_kml_name');
			}
		
		$trail->save();
						
		$activities_checked = Input::get('activities');
		if(is_array($activities_checked))
		{
			$trail->activity()->sync($activities_checked);
		}
				
		$amenities_checked = Input::get('amenities');
		if(is_array($amenities_checked))
		{
			$trail->amenity()->sync($amenities_checked);
		} else {
			$amenities_checked = array();
			$trail->amenity()->sync($amenities_checked);
		}
		
		$image_ids = Input::get('imageids');
		if(is_array($image_ids))
		{
			$trail->image()->sync($image_ids);
		}
		
		$point_ids = Input::get('pointids');
		if(is_array($point_ids))
		{
			$trail->point()->sync($point_ids);
		}
		
		return Redirect::to('/trails');
	}


	/**
	 * Remove the specified trail.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Trail::find($id)->delete();
        return Redirect::route('trails.index');
	}
	
	public function imageUpload()
	{
		
		if (Input::hasFile('image_file')){
			$name = Input::get('image_name');
			$file = Input::file('image_file');
			$image_name = $file->getClientOriginalName();
			if (!(strlen($name) > 0)) $name = $image_name;
			$file = $file->move( public_path() . '/uploads/images/', $image_name);
			$image = new Image;
			$image->name = $name;
			$image->file = $image_name;
			$image->save();

			$image_id = $image->id;

			$image_link = "<img src='http://trails.greenways.ca/uploads/images/" . rawurlencode($image_name) . "' class='preview' />";
			
			$response = '<div>' . $image_link . '<input type="hidden" name="imageids[]" value="' . $image_id . '">  <button class="delete">Delete</button></div>';
			return $response;
		}
		
		return '<div>You need to include an image.  <button class="delete">Delete</button></div>';;
		
	}

	public function pointUpload()
	{
		
		if (Input::hasFile('point_file')){
			$name = Input::get('point_name');
			$description = Input::get('point_description');	
			$lat = Input::get('point_lat');		
			$lng = Input::get('point_lng');	
			$primary = Input::get('point_primary');				
			$file = Input::file('point_file');
			$point_name = $file->getClientOriginalName();
			if (!(strlen($name) > 0)) $name = $point_name;
			$file = $file->move( public_path() . '/uploads/points/', $point_name);
			$point = new Point;
			$point->name = $name;
			$point->description = $description;	
			$point->lat = $lat;
			$point->lng = $lng;
			$point->primary = $primary;			
			$point->file = $point_name;
			$point->save();

			$point_id = $point->id;

			$point_link = "<img src='http://trails.greenways.ca/uploads/points/" . rawurlencode($point_name) . "' class='preview' />";
			
			$response = '<div>' . $point_link . '<input type="hidden" name="pointids[]" value="' . $point_id . '">  <button class="delete">Delete</button></div>';
			return $response;
		}
		
		return '<div>You need to include an image.  <button class="delete">Delete</button></div>';;
		
	}
	
	public function reviewUpload()
	{
	
		$input = array(
		'comment' => Input::get('comment'),
		'rating'  => Input::get('rating')
		);
		
		$id = Input::get('trailid');
		
		// instantiate Rating model
		$review = new Review;

		// Validate that the user's input corresponds to the rules specified in the review model
		$validator = Validator::make( $input, $review->getCreateRules());

		// If input passes validation - store the review in DB, otherwise return to product page with error message 
		if ($validator->passes()) {
			$review->storeReviewForTrail($id, $input['comment'], $input['rating']);
			return Redirect::to('trails/'.$id.'#reviews-anchor')->with('review_posted',true);
		}
		
		return Redirect::to('trails/'.$id.'#reviews-anchor')->withErrors($validator)->withInput();
	
	}

}
