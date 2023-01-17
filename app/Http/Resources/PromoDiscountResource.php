<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PromoDiscountResource extends JsonResource
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
            'promo_from'=>$this->promo_from,
            'vendor_id'=>$this->vendor_id,
            'value_discount'=>$this->value_discount,
            'status'=>$this->status,
            'start_date'=>$this->start_date,
            'end_date'=>$this->end_date,
        ];
    }
}
