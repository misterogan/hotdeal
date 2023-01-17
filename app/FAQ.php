<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{
    protected $table = 'faqs';

    protected $fillable = [
        'image', 'title', 'question', 'answer', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];
}
