<?php

namespace App\Http\Resources;

use App\Helpers\Utils;
use App\ProductGallery;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckoutProductDetailResource extends JsonResource
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
            'stock'=>$this->stock,
            'label_price' => Utils::currency_convert($this->face_price),
            'price'=>$this->face_price,
            'variant_k_1' => $this->variant_key_1,
            'variant_v_1' => $this->variant_value_1,
            'variant_k_2' => $this->variant_key_2,
            'variant_v_2' => $this->variant_value_2,
            'product_gallery' =>  $this->get_image($this->id),
            'status' => $this->status,
        ];
    }

    private function get_image($product_detail_id){
        $galleries = ProductGallery::select('type','link')->where('product_detail_id',$product_detail_id)->get();
        return $galleries;
    }
}
