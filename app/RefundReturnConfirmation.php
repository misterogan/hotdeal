<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RefundReturnConfirmation extends Model
{
    protected $table = 'refund_return_confirmations';

    protected $fillable = [
        'id', 'consignor','refund_id','receipt_number','shipping_name'
    ];
}
