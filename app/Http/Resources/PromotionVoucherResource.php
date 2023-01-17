<?php

namespace App\Http\Resources;

use App\PromotionVoucher;
use Illuminate\Http\Resources\Json\JsonResource;

class PromotionVoucherResource extends JsonResource
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
            'voucher_name' => $this->voucher_name,
            'voucher_description' => $this->voucher_description,
            'voucher_type'=>$this->voucher_type,
            'vendor_id'=>$this->vendor_id,
            'minimum_payment' => $this->minimum_payment,
            'maximum_promo' => $this->maximum_promo,
            'discount_type' => $this->discount_type,
            'value_discount'=>$this->value_discount,
            'status'=>$this->status,
            'start_date'=>$this->start_date,
            'end_date'=>$this->end_date,
            'image' => $this->image,
            'apply_to_all_product' => $this->apply_to_all_product
        ];

    }
//    private function total_voucher($id){
//        $total = PromotionVoucher::where('category_promotion_id',$id)->count();
//        return $total;
//    }
}
