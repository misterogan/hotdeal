<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hotpoint extends Model
{
    protected $table = 'hotpoints';

    protected $fillable = [
        'id', 'user_id', 'type', 'value', 'before', 'after','code', 'detail', 'created_at', 'updated_at','order_detail_id'
    ];
}
