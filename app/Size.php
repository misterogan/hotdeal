<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $table = 'sizes';

    protected $fillable = [
        'size', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];
}
