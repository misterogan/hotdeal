<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RejekiNomplokWinners extends Model
{
    protected $table = 'rejeki_nomplok_winners';

    protected $fillable = [
        'week_id', 'user_id', 'coupon_id', 'value_hotpoint', 'product_id', 'created_at', 'updated_at'
    ];

    public function week() {
        return $this->hasOne(RejekiNomplokWeek::class, 'id', 'week_id');
    }

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function coupon() {
        return $this->hasOne(RejekiNomplokCoupon::class, 'id', 'coupon_id');
    }

    public function product() {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
