<?php

class Review extends Eloquent
{

  // Validation rules for the ratings
  public function getCreateRules()
  {
    return array(
        'comment'=>'required|min:10',
        'rating'=>'required|integer|between:1,5'
    );
  }

  public function user()
  {
    return $this->belongsTo('User');
  }
  
  public function trail()
  {
    return $this->belongsTo('Trail');
  }

  public function scopeApproved($query)
  {
    return $query->where('approved', true);
  }

  public function scopeSpam($query)
  {
    return $query->where('spam', true);
  }

  public function scopeNotSpam($query)
  {
    return $query->where('spam', false);
  }
  
	public function getTimeagoAttribute()
	{
		$date = \Carbon\Carbon::createFromTimeStamp(strtotime($this->created_at))->diffForHumans();
		return $date;
	}
	
	// this function takes in trail ID, comment and the rating and attaches the review to the product by its ID, then the average rating for the product is recalculated
	public function storeReviewForTrail($trailID, $comment, $rating)
	{
		$trail = Trail::find($trailID);

		$this->user_id = Auth::user()->id;

		$this->comment = $comment;
		$this->rating = $rating;
		$trail->reviews()->save($this);

		// recalculate ratings for the specified product
		$trail->recalculateRating();
	}
}