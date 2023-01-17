<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponDetail extends Model
{
    protected $table = 'coupon_details';
    protected $fillable = [
        'id', 'coupon_id', 'code', 'email', 'claim_date', 'buy_date', 'isActive', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'
    ];

    function value_hotpoint() {
        return $this->belongsTo(Coupon::class, 'coupon_id', 'id');
    }
}
