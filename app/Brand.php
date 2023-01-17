<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = 'brands';
    protected $fillable = [
        'brand_name','status','image','created_at', 'updated_at', 'created_by', 'updated_by'
    ];
}
