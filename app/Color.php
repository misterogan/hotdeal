<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $table = 'colors';

    protected $fillable = [
        'color', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];
}
