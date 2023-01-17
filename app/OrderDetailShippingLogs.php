<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetailShippingLogs extends Model
{
    //
    protected $table = 'order_detail_shipping_logs';

    protected $fillable = [
        'order_detail_id','endpoint','created_at','payload','response','updated_at'
    ];
}
