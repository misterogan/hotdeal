<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromotionVoucherProduct extends Model
{
    protected $table = 'promotion_voucher_products';

    protected $fillable = [
        'promotion_vouchers_id', 'product_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];

    function voucher() {
        return $this->belongsTo(PromotionVoucher::class, 'promotion_vouchers_id');
    }
}
