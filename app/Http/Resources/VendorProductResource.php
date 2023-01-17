<?php

namespace App\Http\Resources;

use App\Product as Product;
use App\ProductGallery;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorProductResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'status' => $this->status,
            'vendor_id' => $this->vendor_id,
            'image' => $this->main_image($this->id),
            'details' => $this->details,
            'category' => $this->category_id
        ];

    }

    private function main_image($id){
        return ProductGallery::where('product_id', $id)->where('status', 'active')->where('is_primary', true)->pluck('link')->first();
    }

}
