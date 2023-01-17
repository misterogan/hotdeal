<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentServiceResource extends JsonResource
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
            'service_name'=>$this->service_name,
            'type_name'=>$this->type_name,
            'volumetric'=>$this->volumetric,
            'minimal_kg'=>$this->min_kg,
            'maximal_kg'=>$this->max_kg,
            'status'=>$this->status,
        ];
    }
}
