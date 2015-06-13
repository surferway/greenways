<?php

class Difficulty extends Eloquent {
	
	// MASS ASSIGNMENT -------------------------------------------------------
	// define which attributes are mass assignable (for security)
	protected $fillable = array('name', 'icon');
	
	protected $table = 'difficulty';


}
