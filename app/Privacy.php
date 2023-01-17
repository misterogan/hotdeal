<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Privacy extends Model
{
    protected $table = 'privacy';

    protected $fillable = [
        'title',
        'description',
        'status',
        'slug',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];
}
