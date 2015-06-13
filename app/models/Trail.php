<?php

class Trail extends Eloquent {
	
	// MASS ASSIGNMENT -------------------------------------------------------
	// define which attributes are mass assignable (for security)
	protected $trail_fields = array('user_id', 'org_id', 'trail', 'location', 'kml', 'distance', 'duration', 'description', 'directions', 'difficulty', 'hazards', 'surface', 'land_access', 'maintenance', 'open', 'active');
	protected $fillable = ['user_id', 'org_id', 'name', 'location', 'kml_name', 'kml_content', 'distance', 'duration', 'trail_head_elevation', 'max_elevation', 'cumulative_elevation', 'elevation_gain', 'start_elevation', 'description', 'end_elevation', 'start_lat', 'start_lng', 'end_lat', 'end_lng', 'directions', 'difficulty', 'hazards', 'surface', 'land_access', 'maintenance', 'season', 'rating', 'open', 'active'];	
	
	// DEFINE RELATIONSHIPS --------------------------------------------------
	public function user() {
		return $this->belongsTo('User');
	}

	public function organization() {
		return $this->belongsTo('Organization', 'org_id'); 
	}
	
	// each trail BELONGS to many activity
	// define our pivot table also
	public function activity() {
		return $this->belongsToMany('Activity', 'activity_trail', 'trail_id', 'activity_id');
	}
	
	public function amenity() {
		return $this->belongsToMany('Amenity', 'amenity_trail', 'trail_id', 'amenity_id');
	}
	
	public function media() {
		return $this->belongsToMany('Media', 'media_trail', 'trail_id', 'media_id');
	}
	
	public function image() {
		return $this->belongsToMany('Image', 'image_trail', 'trail_id', 'image_id');
	}
	
	public function point() {
		return $this->belongsToMany('Point', 'point_trail', 'trail_id', 'point_id');
	}
	
	public function getActiveAttribute($value)
    {
        return (boolean) $value;
    }
	
	public function reviews()
	{
		return $this->hasMany('Review');
	}
	
	public function recalculateRating()
	{
		$reviews = $this->reviews()->notSpam()->approved();
		$avgRating = $reviews->avg('rating');
		$this->rating_cache = round($avgRating,1);
		$this->rating_count = $reviews->count();
		$this->save();
	}
	

}