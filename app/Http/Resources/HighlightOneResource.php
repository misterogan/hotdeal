<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HighlightOneResource extends JsonResource
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
            'square' => $this->img_square,
            'id' => $this->product_id,
            'portrait' => $this->img_portrait,
            'landscape' => $this->img_landscape,
            'slug'=>  $this->products->slug,
            'deep_link' => $this->deep_link,
            'new_tab' => $this->new_tab
        ];
    }
}
