<?php

namespace App\Http\Resources;

use App\ProductDetail;
use App\ViewProduct;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailProductResource extends JsonResource
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
            'product_details_id'=>$this->product_detail_id,
            'quantity'=>$this->quantity,
            'price'=>$this->price,
            'discount_price'=>$this->discount_price,
            'fix_price'=>$this->fix_price,
            'status'=>$this->status,
            'gallery'=> new ProductGalleryResource($this->gallery),
            'product_info'=> $this->get_product($this->product_detail_id),

        ];
    }

    function get_product($product_detail_id){
        $product_details = ViewProduct::select('slug','name','product_id','sku')->with('image')->where('product_detail_id',$product_detail_id)->first();
        return $product_details;

    }
}
