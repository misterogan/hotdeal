<?php

namespace App\Http\Resources\Merchant;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            //'vendor' => $this->vendor,
            'phone' => $this->phone,
            'name' => $this->vendor->name,
            'description' => $this->vendor->description,
            'address' => $this->vendor->address,
            'province' => $this->vendor->province,
            'city' => $this->vendor->city,
            'suburbs' => $this->vendor->suburb,
            'area' => $this->vendor->area,
        ];
    }
}
