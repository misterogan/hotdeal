<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Search extends Model
{
    protected $table = 'searches';

    protected $fillable = [
        'word', 'user_id', 'count', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];
}
