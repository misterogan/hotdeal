<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    protected $table = 'about_us';
    protected $fillable = [
        'image', 'message', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];
}
