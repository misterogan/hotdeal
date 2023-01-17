<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetailShippingTracker extends Model
{
    //
    protected $table = 'order_detail_shipping_trackers';

    protected $fillable = [
        'order_detail_id','created_at','code','tracker','updated_at'
    ];
}
