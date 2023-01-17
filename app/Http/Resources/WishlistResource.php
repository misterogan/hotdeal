<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WishlistResource extends JsonResource
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
            'product_id'=> new ProductsResource($this->product_id),
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
            //'product_id'=> new ProductsResource($this->product_id),
        ];
    }
}
