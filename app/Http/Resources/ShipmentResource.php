<?php

namespace App\Http\Resources;

use App\ShipmentService;
use Cassandra\Collection;
use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'name'=>$this->shipment_name,
            'url'=>$this->logo_url,
            'code'=>$this->code,
            'services'=> ShipmentServiceResource::collection($this->services)
        ];
    }
}
