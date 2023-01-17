<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HighlightProduct extends Model
{
    protected $table = 'highlight_products';

    protected $fillable = [
        'product_id', 'deep_link', 'highlight_type', 'status', 'new_tab', 'sequence', 'created_at', 'updated_at', 'created_by', 'updated_by', 'img_square', 'img_landscape', 'img_portrait'
    ];

    function products(){
        return $this->HasOne(Product::class, 'id', 'product_id');
    }

    function image(){
        return $this->hasOne(ProductGallery::class , 'product_id', 'product_id')->where('status','!=','inactive')->where('type' ,'1');
    }


}
