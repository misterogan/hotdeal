<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    protected $table = 'order_histories';
    protected $fillable = [
        'order_id', 'order_status_id','created_at','updated_at'
    ];
}
