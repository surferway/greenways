<?php

function get_ratings(){
	$ratings_array = array('5' => 'star5', '4' => 'star4', '3' => 'star3', '2' => 'star2', '1' => 'star1');
	//$ratings_col = new Illuminate\Support\Collection($ratings_array);
	return $ratings_array;
}

function errors_for($attribute, $errors)
{
	return $errors->first($attribute, '<span class=error>:message</span>');
}

function distance($lat1, $lon1, $lat2, $lon2, $unit) {
      $theta = $lon1 - $lon2;
      $dist  = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
      $dist  = acos($dist);
      $dist  = rad2deg($dist);
      $miles = $dist * 60 * 1.1515;
      $unit  = strtoupper($unit);
    
      if ($unit == "K") {
          return ($miles * 1.609344);
      } else if ($unit == "N") {
          return ($miles * 0.8684);
      } else {
          return $miles;
      }
}