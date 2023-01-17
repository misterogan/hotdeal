<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'id','user_id', 'transaction_number', 'total_payment', 'total_discount', 'total_insurance','point', 'status', 'created_at', 'updated_at','expired_date', 'created_by', 'updated_by'
    ];

    function master_status(){
        return $this->hasOne(MasterStatusOrder::class, 'id','status');
    }

    function order_detail(){
        return $this->hasMany(OrderDetail::class,'order_id','id');
    }

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function detail() {
        return $this->hasOne(OrderDetail::class, 'order_id');
    }

    public function payment() {
        return $this->hasOne(OrderPayment::class, 'order_id');
    }

    public function payment_lable() {
        return $this->hasOne(OrderPayment::class, 'order_id');
    }

    public function status() {
        return $this->hasOne(OrderStatus::class, 'order_id');
    }

    public function voucher() {
        return $this->hasOne(OrderVouchers::class, 'order_id');
    }

    public function order_payments(){
        return $this->hasOne(OrderPayment::class, 'order_id','id');
    }
    public function order_history(){
        return $this->hasMany(OrderHistory::class, 'order_id','id');
    }
}
