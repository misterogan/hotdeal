<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponPartnerResource extends JsonResource
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
            'buying_date' => $this->buy_date,
            'claim_date'=>$this->claim_date,
            'voucher_code'=>$this->code,
            'value' => $this->value_hotpoint->hotpoint,
            'email'=>$this->email,
            'status'=>$this->status,
        ];
    }
}
