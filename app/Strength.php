<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Strength extends Model
{
    protected $table = 'strengths';

    protected $fillable = [
        'title', 'description', 'image', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];
}
