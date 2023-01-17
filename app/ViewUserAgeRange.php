<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ViewUserAgeRange extends Model
{
    protected $table = 'view_user_age_range';
    protected $fillable = [
       'age_group','age_count'
    ];
}
