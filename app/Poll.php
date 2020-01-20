<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Tymon\JWTAuth\Contracts\JWTSubject;


class Poll extends Model implements JWTSubject, AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'interest', 'poll', 'expirydate', 'startdate',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',  'remember_token', 'email_verified_at','verify_token',
    ];

      public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }
    public function users()
    {
        return $this->belongsTo('App\User', 'owner_id', 'id');
    }

    public function interest()
    {
        return $this->belongsTo('App\Interest');
    }

    public function options()
    {
        return $this->hasMany('App\Option');
    }

    public function votes()
    {
        return $this->hasMany('App\Vote');
    }

    public function vote_status()
    {
        return $this->hasMany('App\Vote');
    }
    public function all_voters()
    {
        return $this->hasMany('App\Vote');
    }

    public function voters_male()
    {
        return $this->hasMany(Vote::class);
    }

    public function getAssociatedViewsCountAttribute() {
        return $this->voters_male->count(); 
    }
}
