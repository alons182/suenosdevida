<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

//use Suenos\Profiles\Profile;
use Baum\Node;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class User extends Node implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['username', 'email', 'password','parent_id'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

    public function scopeSearch($query, $search)
    {
        return $query->where(function($query) use ($search)
        {
            $query->where('username', 'like', '%'.$search.'%')
                ->orWhere('email', 'like', '%'.$search.'%');
        });
    }
    /**
     * Set Hash password
     * @param $password
     * @return string
     */
    public function setPasswordAttribute($password)
    {
        if(!empty($password))
            $this->attributes['password'] = Hash::make($password);

    }

    public function orders()
    {
        return $this->hasMany('App\Order')->latest();
    }
    public function payments()
    {
        return $this->hasMany('App\Payment');
    }
    public function gains()
    {
        return $this->hasMany('App\Gain');
    }
    public function profiles()
    {
        return $this->hasOne('App\Profile');
    }
    public function createProfile($profile = null)
    {
        $profile = ($profile) ? $profile : new Profile();

        return $this->profiles()->save($profile);
    }
    public function roles()
    {
        return $this->belongsToMany('App\Role')->withTimesTamps();
    }
    public function isCurrent()
    {
        if(Auth::guest()) return false;

        return Auth::user()->id == $this->id;
    }
    public function hasRole($name)
    {
        foreach ($this->roles as $role)
        {
            if($role->name == $name) return true;
        }

        return false;

    }
    public function assignRole($role)
    {
        return $this->roles()->attach($role);
    }
    public function removeRole($role)
    {
        return $this->roles()->detach($role);
    }

}
