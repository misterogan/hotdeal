<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserVoucher extends Model
{
    protected $table = 'user_vouchers';

    protected $fillable = [
        'user_id', 'voucher_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];
}
