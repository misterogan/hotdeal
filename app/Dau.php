<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dau extends Model
{
    protected $table = 'dau';
    protected $fillable = [
        'id','user_id','created_at','updated_at'
    ];
}
