<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';

    protected $fillable = [
        'image', 'url', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];
}
