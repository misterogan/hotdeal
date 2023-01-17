<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'coupons';
    protected $fillable = [

        'id', 'coupon_name', 'start_date', 'expired_date', 'created_by', 'updated_by', 'created_at', 'updated_at','total_coupon','status',
        'partner_id','serial_code','length_code','hotpoint'
    ];

}
