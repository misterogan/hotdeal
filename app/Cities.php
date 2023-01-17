<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    protected $table = 'cities';
    protected $fillable = [
        'id','api_id','province_id','name', 'lat', 'lng','created_at','updated_at'
    ];
}
