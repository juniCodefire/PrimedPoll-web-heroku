<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Poll;


class User extends Model implements JWTSubject, AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
      
        'first_name', 'last_name', 'email', 'phone', 'category', 'dob', 'api_token',  'password', 'verifycode'


    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',  'remember_token', 'email_verified_at','verifycode',
    ];
  
      public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }

  
    public function polls()
    {
        return $this->hasMany('App\Poll');
    }

    public function interest()
    {
        return $this->belongsToMany('App\Interest', 'userinterests', 'owner_id', 'interest_id');
    }

    public function options()
    {
        return $this->hasMany('App\Option');
    }

    public function votes()
    {
        return $this->hasMany('App\Vote');
    }
}
