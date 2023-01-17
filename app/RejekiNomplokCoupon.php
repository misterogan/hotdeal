<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class RejekiNomplokCoupon extends Model
{
    protected $table = 'rejeki_nomplok_coupons';
    protected $fillable = [
        'user_id', 'rejeki_nomplok_week_id','has_send_point' ,'is_winner' ,'point_sent','value_transaction', 'order_details_id', 'product_id', 'coupon_number','status', 'created_at', 'updated_at'
    ];

    // public function getCreatedAtAttribute($date)
    // {
    //     return Carbon::parse($date)->translatedFormat('j F Y H:i:s');
    // }

    // public function getUpdatedAtAttribute($date)
    // {
    //     return Carbon::parse($date)->translatedFormat('j F Y H:i:s');
    // }
    


    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function week() {
        return $this->hasOne(RejekiNomplokWeek::class, 'id', 'rejeki_nomplok_week_id');
    }

    public function order() {
        return $this->hasOne(OrderDetail::class, 'id', 'order_details_id');
    }

    public function product() {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
    // public function order_details(){
    //     return $this->hasOne(OrderDetailProduct::class , 'id' , )
    // }
}
