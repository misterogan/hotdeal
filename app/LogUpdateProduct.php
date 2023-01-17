<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogUpdateProduct extends Model
{
    protected $table = 'log_update_products';
    protected $fillable = [
        'id', 'product_id', 'before', 'compare', 'after', 'type', 'updated_by', 'created_at', 'updated_at'
    ];

    public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }

    public function vendor(){
        return $this->hasOneThrough(
            Product::class,
            Vendor::class,
            'id',
            'vendor_id',
            'id',
            'id'
        );
    }
}
