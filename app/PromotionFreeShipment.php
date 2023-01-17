<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromotionFreeShipment extends Model
{
    protected $table = 'promotion_free_shipments';

    protected $fillable = [
        'promo_from', 'vendor_id', 'minimum_payment', 'maximum_payment', 'shipping_fee_discount', 'status', 'start_date', 'end_date', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];
}
