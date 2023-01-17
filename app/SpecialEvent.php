<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpecialEvent extends Model
{
    protected $table = 'special_events';
    protected $fillable = [
        'id', 'event_name', 'banner_type', 'banner_image', 'banner_mobile', 'status', 'about', 'tnc', 'slug', 'start_date', 'end_date', 'created_by', 'updated_by', 'created_at', 'updated_at'
    ];
}
