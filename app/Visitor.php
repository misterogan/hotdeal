<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $table = 'visitors';
    public $timestamps = false;

    protected $fillable = [
        'date', 'ip', 'slug', 'vendor_id'
    ];
}
