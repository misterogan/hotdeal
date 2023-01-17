<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionProductRefundLog extends Model
{
    protected $table = 'transaction_product_refund_logs';

    protected $fillable = [
        'transactions_invoice_products_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];
}
