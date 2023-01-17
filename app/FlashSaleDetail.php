<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlashSaleDetail extends Model
{
    protected $table = 'flash_sale_details';

    protected $fillable = [
        'flash_sale_id', 'product_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];
    
    function products(){
        return $this->hasOne(Product::class,'id' , 'product_id')->with('galleries' , 'detail');
    }
}
