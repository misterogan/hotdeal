<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorShipment extends Model
{
    protected $table = 'vendor_shipment_services';

    protected $fillable = [
        'vendor_id','shipment_service_id', 'created_at', 'updated_at'
    ];
}
