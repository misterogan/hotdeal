<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryTransaction extends Model
{
    protected $table = 'history_transaction';

    protected $fillable = [
        'user_id', 'product_id', 'sku', 'quantity', 'voucher_id', 'price', 'courier', 'delivered_date', 'delivery_cost', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];
}
