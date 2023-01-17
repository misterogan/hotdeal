<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductSuggestion extends Model
{
    protected $table = 'product_suggestions';
    protected $fillable = [
        'product_id','status','created_at', 'updated_at', 'created_by', 'updated_by'
    ];

    public function product(){
        return $this->hasOne(Product::class,'id');
    }
}
