<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestingJob extends Model
{
    public $timestamps = false;
    protected $table = 'testing_job';
    protected $fillable = [
        'id','name'
    ];
}
