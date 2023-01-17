<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShipmentService extends Model
{
    protected $table = 'shipment_services';

    protected $fillable = [
        'id','shipment_services_shipper_id','shipment_services_id', 'service_name', 'type_name', 'volumetric', 'min_kg', 'max_kg', 'status', 'created_at', 'updated_at','created_by','updated_by'
    ];
}
