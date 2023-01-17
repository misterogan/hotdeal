<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

class OrderDetail extends Model
{
    
    
    protected $table = 'order_details';
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'order_id', 'vendor_id', 'invoice_number', 'insurance_fee', 'invoice_total_payment', 'invoice_total_discount',  'created_at', 'updated_at', 'status','slug', 'estimasi'
    ];
    
    // public function getCreatedAtAttribute($date)
    // {
    //     return Carbon::parse($date)->translatedFormat('j F Y H:i:s');
    // }

    // public function getUpdatedAtAttribute($date)
    // {
    //     return Carbon::parse($date)->translatedFormat('j F Y H:i:s');
    // }

    public function order_products(){
        return $this->hasMany(OrderDetailProduct::class,'order_detail_id','id');
    }

    public function vendor(){
        return $this->hasOne(Vendor::class,'id','vendor_id');
    }

    public function status_order(){
        return $this->hasOne(MasterStatusOrder::class, 'id', 'status')->select('id' , 'description' , 'status_code');
    }

    public function payment() {
        return $this->hasOne(OrderPayment::class, 'order_id','order_id');
    }

    public function order() {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function product() {
        return $this->hasOne(OrderDetailProduct::class, 'order_detail_id');
    }

    public function products() {
        return $this->hasMany(OrderDetailProduct::class, 'order_detail_id');
    }

    public function productswithdetail() {
        return $this->hasMany(OrderDetailProduct::class, 'order_detail_id')->with(
            ['product_detail_with_product' => function($q){
                $q->join('products' , 'product_details.product_id' , 'products.id')
                ->join('product_galleries' , 'products.id' , 'product_galleries.product_id')
                ->where('product_galleries.type' ,'1')->where('product_galleries.status' ,'active')
                ->select('products.name' , 'product_details.id', 'products.id as product_id' ,'product_galleries.link', 'product_details.variant_value_1', 'product_details.variant_value_2');
            }]
        );
    }

    public function productswithdetailwithimage() {
        return $this->hasMany(OrderDetailProduct::class, 'order_detail_id')->with(
            ['product_detail_with_product' => function($q){
                $q->join('products' , 'product_details.product_id' , 'products.id')
                ->join('product_galleries' , 'products.id' , 'product_galleries.product_id')
                ->where('product_galleries.type' ,'1')->where('product_galleries.status' ,'active')
                ->select('products.name' , 'product_details.id' ,'product_galleries.link');
            }]
        );
    }

    public function shipping() {
        return $this->hasOne(OrderDetailShipping::class, 'order_details_id');
    }

    public function order_logs(){
        return $this->hasMany(OrderDetailLog::class, 'order_details_id')->with('master_status');
    }

    public function master_status() {
        return $this->hasOne(MasterStatusOrder::class, 'id','status');
    }

    public function refund() {
        return $this->hasOne(Refund::class, 'order_details_id');
    }

    public function review(){
        return $this->hasMany(Review::class, 'order_details_id');
    }

    /**
     * Get all of the productDetail for the OrderDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
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

    public function scopeTransaksiFilter($query, $trx)
    {
        return $query->whereHas('order', function($q) use ($trx){
            $q->where('transaction_number', 'LIKE' , '%'.$trx.'%');
        });
    }

    public function scopeVendor($query, $vendor)
    {
        return $query->whereHas('vendor', function($q) use ($vendor){
            $q->where('name', 'LIKE' , '%'.$vendor.'%');
        });
    }

    public function scopeProductName($query, $product)
    {
        return $query->whereHas('productDetail', function($q) use ($product){
            $q->whereHas('product', function($q1) use ($product){
                $q1->where('name', 'LIKE' , '%'.$product.'%');
            });
        });
    }

    public function scopeStatusOrder($query, $status)
    {
        return $query->whereHas('master_status', function($q) use ($status){
            $q->where('description', 'LIKE' , '%'.$status.'%');
        });
    }


    public function productswithdetailadminfee() {
        return $this->hasMany(OrderDetailProduct::class, 'order_detail_id')->with(
            ['product_detail_with_product' => function($q){
                $q->join('products' , 'product_details.product_id' , 'products.id')
                ->join('product_galleries' , 'products.id' , 'product_galleries.product_id')
                ->where('product_galleries.type' ,'1')->where('product_galleries.status' ,'active')
                ->select('products.name' , 'product_details.id', 'products.id as product_id' ,'product_galleries.link','products.admin_fee', 'product_details.variant_value_1', 'product_details.variant_value_2');
            }]
        );
    }


}
