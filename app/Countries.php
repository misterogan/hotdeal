<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Countries extends Model
{
    protected $table = 'countries';
    public $timestamps = false;
    protected $fillable = [
        'name','code','phone_code'
    ];
}
