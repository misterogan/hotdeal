<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BannerEvent extends Model
{
    protected $table = 'banner_events';
    protected $fillable = [
        'image','url','status','created_at', 'updated_at', 'created_by', 'updated_by'
    ];
}
