<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromotionDiscountProduct extends Model
{
    protected $table = 'promotion_discount_products';

    protected $fillable = [
        'vendor_id', 'product_id', 'type', 'value_discount', 'created_at', 'updated_at', 'created_by', 'updated_by', 'promotion_discounts_id'
    ];
}
