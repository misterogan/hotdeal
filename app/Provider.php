<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $table = 'providers';
    protected $fillable = [
        'provider','provider_id','user_id','avatar','created_at','updated_at'
    ];
}
