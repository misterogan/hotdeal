<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderVouchers extends Model
{
    protected $table = 'order_vouchers';
    protected $fillable = [
        'id','order_id','detail_voucher','vendor_id','voucher_id','voucher_value','created_at', 'updated_at'
    ];

    public function voucher(){
        return $this->belongsTo(PromotionVoucher::class,'voucher_id');
    }
}

