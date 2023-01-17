<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    protected $table = 'product_details';
    protected $fillable = [
        'id','product_id','stock','price','status','variant_key_1','variant_value_1','variant_key_2','variant_value_2', 'variation_code', 'child_sku','product_galleries_id','image','created_at','updated_at','created_by','updated_by'
    ];

    // public function getVariantKey1Attribute($string)
    // {
    //     return strlen($string) > 0 ? $string : "";
    // }

    public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }

    public function productname(){
        return $this->hasOne(Product::class,'id','product_id')->select('name','id');
    }

    public function product_discount(){
        return $this->hasOne(PromotionDiscountProduct::class,'product_detail_id','id');
    }

    public function image (){
        return $this->hasOne(ProductGallery::class,'product_id','id')->where('type' , '1');
    }

    public function category (){
        return $this->hasOne(Category::class,'category_id','id')->where('status' , 'active');
    }
}

