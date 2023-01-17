<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromotionDiscount extends Model
{
    protected $table = 'promotion_discounts';

    protected $fillable = [
        'promo_from', 'vendor_id', 'type', 'value_discount', 'status', 'start_date', 'end_date', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];

    public function vendor() {
        return $this->hasOne(Vendor::class, 'id', 'vendor_id');
    }

    public function discount_products() {
        return $this->hasMany(PromotionDiscountProduct::class, 'vendor_id', 'vendor_id');
    }
}
