<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BannerAdditional extends Model
{
    protected $table = 'banner_additionals';
    protected $fillable = [
        'id', 'image', 'status', 'position', 'created_by', 'updated_by', 'created_at', 'updated_at'
    ];
}
