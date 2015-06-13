<?php

class Media extends Eloquent {
	
	// MASS ASSIGNMENT -------------------------------------------------------
	// define which attributes are mass assignable (for security)
	// we only want these 3 attributes able to be filled
	protected $fillable = array('title', 'type', 'file');
	
	protected $table = 'media';

	// DEFINE RELATIONSHIPS --------------------------------------------------
	// define a many to many relationship
	// also call the linking table
	public function trails() {
		return $this->belongsToMany('Trail', 'media_trail', 'media_id', 'trail_id');
	}

}