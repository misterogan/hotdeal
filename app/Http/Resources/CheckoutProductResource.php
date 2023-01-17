<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CheckoutProductResource extends JsonResource
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
            'weight'=>$this->weight,
            'height'=>$this->height,
            'length'=>$this->length,
            'cod'=>$this->cod,
            'for_order'=>$this->for_order,
            'vendor'=> $this->vendor,
        ];
    }
}
