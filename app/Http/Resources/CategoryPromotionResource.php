<?php

namespace App\Http\Resources;

use App\PromotionVoucher;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryPromotionResource extends JsonResource
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
            'category'=>$this->category,
            'total_voucher'=>$this->total_voucher($this->id)
        ];
    }

    private function total_voucher($id){
        $total = PromotionVoucher::where('category_promotion_id',$id)->count();
        return $total;
    }
}
