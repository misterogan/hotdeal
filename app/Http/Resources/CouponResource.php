<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
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
            'vendor_id' => $this->vendor_id,
            'voucher_id'=>$this->id,
            'voucher_code'=>$this->voucher_code,
            'voucher_name'=>$this->voucher_name,
            'voucher_decription'=>$this->voucher_description,
            'voucher_image'=>$this->image,
            'voucher_value'=>$this->value_discount,
            'start_date'=>$this->start_date,
            'minimum_payment'=>$this->minimum_payment,
            'end_date'=>$this->end_date,
        ];
    }
}
