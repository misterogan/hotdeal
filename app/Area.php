<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'areas';
    protected $fillable = [
        'id','api_id','suburb_id','name', 'lat', 'lng','created_at','updated_at'
    ];
}
