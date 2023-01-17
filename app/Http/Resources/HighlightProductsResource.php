<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HighlightProductsResource extends JsonResource
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
            'product_id' => $this->product_id,
            'highlight_type' => $this->highlight_type,
            'status' => $this->status,
            'sequence' => $this->sequence,
            'img_square' => $this->img_square,
            'img_portrait' => $this->img_portrait,
            'img_landscape' => $this->img_landscape,
            'image' => $this->image,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'products'=>  new ProductsResource($this->products),
            'square' => $this->img_square,
            'portrait' => $this->img_portrait,
            'landscape' => $this->img_landscape,
        ];
    }
}
