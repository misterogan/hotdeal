<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ViewProduct extends Model
{
    protected $table = 'view_products';

    protected $fillable = [
        'product_id','product_detail_id','name','slug', 'admin_fee', 'product_status', 'price','variant_key_1','variant_value_1','variant_key_2','variant_value_2',
        'stock','value_discount','amount_discount','face_price','product_details_status','category','brand','shipping_fee_discount_value'
    ];
    function category() {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
    function image() {
        return $this->hasOne(ProductGallery::class, 'product_id', 'product_id')->where('type' , 1);
    }
}
