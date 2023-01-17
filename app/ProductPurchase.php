<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductPurchase extends Model
{
    protected $table = 'product_purchases';
    protected $fillable = [
        'id', 'product_id', 'status', 'total', 'created_at', 'updated_at'
    ];
}
