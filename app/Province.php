<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = 'provinces';
    protected $fillable = [
        'id','api_id','name', 'lat', 'lng','source','created_at','updated_at'
    ];
}
