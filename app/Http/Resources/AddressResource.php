<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'itemid' => $this->id,
            'recipient_name'=>$this->recipient_name,
            'phone_number'=>$this->phone_number,
            'address'=>$this->address,
            'longitude' => $this->lng,
            'latitude' => $this->lat,
            'province_id'=>$this->province,
            'regency_id'=>$this->regency,
            'area_id'=>$this->area_point,
            'city_id'=>$this->city,
            'zip_code'=> $this->zip_code,
            'label_name'=>$this->label_name,
            'is_primary_address'=>$this->is_primary_address,
            'status'=>$this->status,
            'area_lat' => $this->area_point->lat,
            'is_picked_location' => $this->area_point->lat == $this->lat ? true : false
        ];
    }
}
