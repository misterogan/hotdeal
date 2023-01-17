<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Suburbs extends Model
{
    protected $table = 'suburbs';
    protected $fillable = [
        'id','api_id','city_id','name', 'lat', 'lng','created_at','updated_at'
    ];
}
