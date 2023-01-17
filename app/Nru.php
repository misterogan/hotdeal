<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nru extends Model
{
    protected $table = 'nrus';
    protected $fillable = [
        'id','user_id','created_at','updated_at'
    ];
}
