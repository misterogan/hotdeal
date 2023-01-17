<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromotionVoucher extends Model
{
    protected $table = 'promotion_vouchers';

    protected $fillable = [
        'voucher_name', 'voucher_description', 'show_only', 'voucher_type', 'vendor_id', 'voucher_code', 'minimum_payment', 'maximum_promo', 'discount_type', 'value_discount', 'status', 'start_date', 'end_date', 'image', 'apply_to_all_product','apply_to_all_user','user_id', 'created_at', 'updated_at', 'created_by', 'updated_by','category_promotion_id', 'total', 'is_code', 'is_multiple', 'amount_product_only'
    ];

    function products() {
        return $this->hasMany(PromotionVoucherProduct::class, 'promotion_vouchers_id');
    }

    function orderVoucher() {
        return $this->hasMany(OrderVouchers::class, 'voucher_id');
    }

    function category() {
        return $this->belongsTo(Category::class, 'category_promotion_id', 'id');
    }

    public function getTotalVoucherAttribute()
    {
        // dd($value);
        return $this->orderVoucher->count();
    }


}
