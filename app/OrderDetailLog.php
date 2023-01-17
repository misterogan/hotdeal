<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetailLog extends Model
{
    protected $table = 'order_detail_logs';

    protected $fillable = [
        'order_details_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by','description'
    ];

    public function master_status() {
        return $this->hasOne(MasterStatusOrder::class, 'id' ,'status')->select('description','id','status_code','description_vendor');
    }

    public function order_products(){
        return $this->hasOne(OrderDetailProduct::class,'order_detail_id','id');
    }

    public function order(){
        return $this->hasOneThrough(
            Order::class,
            OrderDetail::class,
            'id',
            'id',
            'order_details_id'
        );
    }

    public function productDetail()
    {
        return $this->hasManyThrough(
            ProductDetail::class,
            OrderDetailProduct::class,
            'order_detail_id', // yang berhubungan ke order detail product detail
            'id', //yg ada di product detail
            'id', // id order detail
            'product_detail_id' // yg menghubungkan ke product detail
        );
    }

}
