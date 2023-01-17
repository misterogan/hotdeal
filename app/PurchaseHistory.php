<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseHistory extends Model
{
    protected $table = 'purchase_history';

    protected $fillable = [
        'user_id', 'product_id', 'quantity', 'voucher_used_id', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];
}
