<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RefundAccountBank extends Model
{
    protected $table = 'refund_account_banks';

    protected $fillable = [
        'id', 'user_id', 'account_name', 'account_number', 'refund_id', 'bank_name','created_at', 'updated_at','identity_image'
    ];
}
