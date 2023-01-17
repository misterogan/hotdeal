<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromotionVoucherVendor extends Model
{
    protected $table = 'promotion_voucher_vendors';

    protected $fillable = [
        'promotion_vouchers_id', 'vendor_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];
}
