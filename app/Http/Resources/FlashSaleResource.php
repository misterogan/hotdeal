<?php

namespace App\Http\Resources;

use App\FlashSaleDetail;
use App\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class FlashSaleResource extends JsonResource
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
            'banner_type'=>$this->banner_type,
            'status'=>$this->status,
            'start_date'=>$this->start_date,
            'end_date'=>$this->end_date,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
            'flash_sale_detail'=> FlashSaleDetailResource::collection($this->flash_sale_detail),

        ];
    }
}
