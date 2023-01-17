<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductShipmentService extends Model
{
    protected $table = 'product_shipment_services';

    protected $fillable = [
        'product_id','shipment_service_id', 'created_at', 'updated_at'
    ];

}
