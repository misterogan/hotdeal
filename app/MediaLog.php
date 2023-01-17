<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MediaLog extends Model
{
    protected $table = 'media_log';
    protected $fillable = [
        'log','created_at','updated_at'
    ];
}
