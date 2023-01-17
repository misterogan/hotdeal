<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart';

    protected $fillable = [
        'user_id', 'product_details_id','status', 'quantity', 'created_at', 'updated_at', 'created_by', 'updated_by','in_checkout'
    ];

    public function product_detail(){
        return $this->hasOne(ProductDetail::class,'id','product_details_id');
    }

    public function view_product(){
        return $this->hasOne(ViewProducts::class,'product_detail_id','product_details_id');
    }

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }

    public function user_addresses(){
        return $this->hasOne(UserAddress::class,'user_id','user_id')->where('is_primary_address',true);
    }

    public function products(){
        return $this->hasOne(ViewProduct::class,'product_detail_id','product_details_id');
    }
}
