<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PromoDiscountProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return
            [
                'promotion_vouchers_id'=>$this->promotion_discounts_id,
                'product_detail_id'=>$this->product_detail_id,

            ];
    }
}
