<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogVoucherUsage extends Model
{
    protected $table = 'log_voucher_usages';
    protected $fillable = [
        'id',
        'promotion_voucher_id',
        'user_id',
        'code',
        'created_at',
        'updated_at'
    ];
}
