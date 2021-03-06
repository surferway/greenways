<?php

class Point extends Eloquent {
	
	// MASS ASSIGNMENT -------------------------------------------------------
	// define which attributes are mass assignable (for security)
	// we only want these 3 attributes able to be filled
	protected $fillable = array('name', 'file', 'description', 'lat', 'lng', 'primary');
	
	protected $table = 'points';

	// DEFINE RELATIONSHIPS --------------------------------------------------
	// define a many to many relationship
	// also call the linking table
	public function trails() {
		return $this->belongsToMany('Trail', 'point_trail', 'point_id', 'trail_id');
	}

}