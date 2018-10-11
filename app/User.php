<?php

namespace App;

use Illuminate\Notifications\Notifiable;
/* use Illuminate\Contracts\Auth\MustVerifyEmail; */
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'birthdate', 'email', 'password', 'user_role', 'image'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getJWTIdentifier()
        {
            return $this->getKey();
        }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
