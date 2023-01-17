<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'page';

    protected $fillable = [
        'image',
        'title',
        'description',
        'status',
        'slug',
        'created_at',
        'updated_at',
        'created_by',
    ];
}
