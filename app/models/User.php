<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	
	  /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';
	
	protected $primaryKey = 'id';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password', 'remember_token');
	
	
	protected $fillable = ['username', 'email', 'password'];
	
	//'email' => 'required|unique:users,email',	
	public static $rules = [
		'username' => 'required',
		'email' => 'required|unique:users|email',		
		'password' => 'min:6|required',
		'password_confirmation' => 'min:6|same:pass'	
	];
	
	public $errors;
	
	public function getRememberTokenName()
	{
		return 'remember_token';
	}

	public function getReminderEmail()
	{
		return $this->email;
	}

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	//protected $hidden = array('password', 'password_confirmation', 'remember_token');
	
	public function organization() {
		return $this->belongsTo('Organization', 'org_id'); 
	}
	
	// each user creates many trails
	public function trails() {
		return $this->hasMany('Trail');
	}
	
	public function reviews()
	{
		return $this->hasMany('Review');
	}
	
	/*
	public function activities()
    {
        return $this->belongsToMany('Activity');
    }
	
	public function amenities()
    {
        return $this->belongsToMany('Amenity');
    }
	*/
	
	public function isValid()
	{
		$validation = Validator::make($this->attributes, static::$rules);
		
		if ($validation->passes())
		{
			return true;
		}
		
		$this->errors = $validation->messages();
		
		return false;
		
	}	
	
	    /**
     * @return mixed
     */
    public function roles()
    {
        return $this->belongsToMany('Role')->withTimestamps();
    }

    /**
     * Does the user have a particular role?
     *
     * @param $name
     * @return bool
     */
    public function hasRole($name)
    {
        foreach ($this->roles as $role)
        {
            if ($role->name == $name) return true;
        }

        return false;
    }

    /**
     * Assign a role to the user
     *
     * @param $role
     * @return mixed
     */
    public function assignRole($role)
    {
        return $this->roles()->attach($role);
    }

    /**
     * Remove a role from a user
     *
     * @param $role
     * @return mixed
     */
    public function removeRole($role)
    {
        return $this->roles()->detach($role);
    }
	
	/**
     * Find by email, or throw an exception.
     *
     * @param string $email The email.
     * @param mixed $columns The columns to return.
     *
     * @throws ModelNotFoundException if no matching User exists.
     *
     * @return User
     */
	public static function findByEmailOrFail(
        $email,
        $columns = array('*')
    ) {
        if ( ! is_null($user = static::whereEmail($email)->first($columns))) {
            return $user;
        }

        throw new ModelNotFoundException;
    }
	
	/**
     * Find by username, or throw an exception.
     *
     * @param string $username The username.
     * @param mixed $columns The columns to return.
     *
     * @throws ModelNotFoundException if no matching User exists.
     *
     * @return User
     */
	public static function findByUsernameOrFail(
        $username,
        $columns = array('*')
    ) {
        if ( ! is_null($user = static::whereUsername($username)->first($columns))) {
            return $user;
        }

        throw new ModelNotFoundException;
    }

}
