<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'name','vendor_id','category_id', 'brand_id','dimension', 'brand', 'description', 'short_desc', 'slug', 'admin_fee', 'weight','height','length','width','cod','for_order', 'sku', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];

    function review(){
        return $this->hasMany(Review::class,'product_id');
    }

    public function detail(){
        return $this->hasMany(ProductDetail::class , 'product_id')->where('status' , 'active')->orderBy('price','ASC');
    }

    public function details() {
        return $this->hasMany(ProductDetail::class , 'product_id', 'id')->whereIn('status' , ['active','inactive']);
    }

    public function detail_hasone() {
        return $this->hasOne(ProductDetail::class , 'product_id', 'id');
    }

    public function discount() {
        return $this->hasOne(PromotionDiscountProduct::class, 'product_id');
    }

    public function promotion() {
        return $this->hasOne(PromotionDiscount::class, 'vendor_id', 'vendor_id');
    }

    public function gallery() {
        return $this->hasOne(ProductGallery::class , 'product_id');
    }

    function galleries(){
        return $this->hasMany(ProductGallery::class , 'product_id')->where('status','!=','inactive')->orderBy('type' , 'DESC')->orderBy('is_primary', 'DESC')->orderBy('id');
    }

    function image(){
        return $this->hasOne(ProductGallery::class , 'product_id')->where('status','!=','inactive')->where('type' ,'1');
    }

    function images() {
        return $this->hasMany(ProductGallery::class , 'product_id')->where('status','!=','inactive')->where('type' ,'1')->whereNull('product_variant_image')->orWhere('product_detail_id','=','0')->orderBy('id');
    }

    function video(){
        return $this->hasOne(ProductGallery::class , 'product_id')->where('status','!=','inactive')->where('type' ,'2');
    }
    function main_photo(){
        return $this->hasOne(ProductGallery::class , 'product_id')->where('status','!=','inactive')->where('type' ,'1')->where('is_primary' , true);
    }
    function photo(){
        return $this->hasMany(ProductGallery::class , 'product_id')->where('status','!=','inactive')->where('type' ,'1')->where('is_primary' ,'!=', true)->where('product_detail_id', 0)->orderBy('type' , 'DESC'); //return $this->hasOne(ProductGallery::class , 'product_id')->where('status','!=','inactive')->where('type' ,'1')->where('is_primary' , false);
    }
    function in_wish_list(){
        return $this->hasOne(Wishlist::class , 'product_id')->where('status' ,'active');
    }

    function category() {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    function brand() {
        return $this->hasOne(Brand::class, 'id', 'brand_id');
    }

    function vendor(){
        return $this->hasOne(Vendor::class,'id','vendor_id')->select('id','name','image','province_id','area_id','suburb_id','city_id');
    }

    function rejeki_nomplok() {
        return $this->hasMany(RejekiNomplokProduct::class , 'product_id');
    }
    
    function primary_image(){
        return $this->hasMany(ProductGallery::class , 'product_id')->where('status','!=','inactive')->where('type' ,'1')->whereNull('product_detail_id');
    }
}
