<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HotpointCode extends Model
{
    protected $table = 'hotpoint_codes';

    protected $fillable = [
        'id','code','description','status','created_at','updated_at'
    ];
}
