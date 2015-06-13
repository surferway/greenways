<?php

class Activity extends Eloquent {
	
	// MASS ASSIGNMENT -------------------------------------------------------
	// define which attributes are mass assignable (for security)
	// we only want these 3 attributes able to be filled
	protected $fillable = array('name', 'icon', 'button', 'mini', 'marker');
	
	protected $table = 'activities';

	// DEFINE RELATIONSHIPS --------------------------------------------------
	// define a many to many relationship
	// also call the linking table
	public function trails() {
		return $this->belongsToMany('Trail', 'activity_trail', 'activity_id', 'trail_id');
	}

}
