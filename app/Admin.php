<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticable
{
    use Notifiable;

    protected $guard = 'admin';

    protected $fillable = [
        'name', 'email', 'email_verified_at', 'password', 'remember_token', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];

    protected $hidden = ['password'];
}
