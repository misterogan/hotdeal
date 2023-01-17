<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    protected $table = 'order_status';

    protected $fillable = [
        'order_id', 'master_status_order_id', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];
}
