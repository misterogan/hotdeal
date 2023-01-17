<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetailProduct extends Model
{
    protected $table = 'order_detail_products';

    protected $fillable = [
        'order_detail_id','product_detail_id', 'quantity', 'price', 'discount_price', 'fix_price','admin_fee', 'promotion_vouchers_id', 'promotion_discount_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'notes'
    ];

    function product(){
        return $this->hasOne(Product::class, 'id','product_id');
    }

    function product_rejeki_nomplok(){
        return $this->hasOne(ProductDetail::class , 'id' , 'product_detail_id');
    }

    function gallery(){
        return $this->hasOne(ProductGallery::class,'product_detail_id','product_detail_id')->where('type' , '1');
    }

    function galleries (){
        return $this->hasMany(ProductGallery::class,'product_detail_id','product_detail_id');
    }

    public function product_detail() {
        return $this->hasOne(ProductDetail::class, 'id', 'product_detail_id');
    }

    public function product_detail_with_product() {
        return $this->hasOne(ProductDetail::class, 'id', 'product_detail_id');
    }

    public function product_fee(){
        return $this->hasOneDeep(ProductDetail::class, [Product::class]);
    }

}
