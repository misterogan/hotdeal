<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $table = 'wishlist';

    protected $fillable = [
        'user_id', 'product_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];

    public function product(){
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

}
