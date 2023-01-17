<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'registration_source', 'parent_id', 'referal_code', 'password', 'image', 'phone','payment_id','is_vendor','status','dob','gender','point','is_email_verified','is_phone_verified','email_verified_at',
    ];

    //protected $visible = ['name', 'email','is_vendor','vendor'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','pin'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function user_addresses(){
        return $this->hasMany(UserAddress::class,'user_id','id')->where('is_primary_address',true);
    }

    public function provider(){
        return $this->hasMany(Provider::class,'user_id','id');
    }

    public function province() {
        return $this->hasOne(Province::class,'id','province');
    }

    public function vendor(){
        return $this->hasOne(Vendor::class , 'user_id' , 'id');
    }

}
