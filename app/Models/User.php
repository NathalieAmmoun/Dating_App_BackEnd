<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

use App\Models\UserType;
use App\Models\UserConnection;
use App\Models\UserPicture;
use App\Models\UserInterest;
use App\Models\UserHobby;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function userName(){
        return $this->name;
    }

    public function userConnections(){

        return $this->hasMany(UserConnection::class, 'user1_id');
    }

    public function userPictures(){

        return $this->hasMany(UserPicture::class);
    }

    public function userHobbies(){

        return $this->hasMany(UserHobby::class);
    }

    public function userInterests(){

        return $this->hasMany(UserInterest::class);
    }



	public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


}
