<?php

namespace App;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Backlink extends Model
{
    //use HasFactory;
    protected $table = 'backlinks';
    protected $fillable = [
        'id', 
        'ip_address',
        'referer',
        'referal_code', 
        'device',
        'user_agent',
        'created_at', 
        'updated_at'
    ];
}
