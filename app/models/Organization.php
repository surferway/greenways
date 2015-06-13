<?php

class Organization extends Eloquent {
	
	// MASS ASSIGNMENT -------------------------------------------------------
	// define which attributes are mass assignable (for security)
	protected $fillable = array('organization');


	// DEFINE RELATIONSHIPS --------------------------------------------------
	
	public function users() {
		return $this->hasMany('User');
	}
	
	public function trails() {
		return $this->hasMany('Trail');
	}

}