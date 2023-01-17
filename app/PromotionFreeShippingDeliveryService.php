<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromotionFreeShippingDeliveryService extends Model
{
    protected $table = 'promotion_free_shipping_delivery_services';

    protected $fillable = [
        'promotion_free_shipping_id', 'shipment_service_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];
}
