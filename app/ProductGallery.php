<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductGallery extends Model
{
    protected $table = 'product_galleries';

    protected $fillable = [
        'product_id', 'product_variant_image', 'product_detail_id', 'status', 'type', 'link', 'created_at','status','is_primary', 'updated_at', 'created_by', 'updated_by'
    ];

    public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }

}
