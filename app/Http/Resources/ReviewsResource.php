<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewsResource extends JsonResource
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
            'product_id'=>$this->product_id,
            'rating'=>$this->rating,
            'review'=>$this->review,
            'user'=> $this->user,
            'review_gallery'=>$this->review_gallery,
            'with_video'=>$this->is_with_video,
        ];
    }
}
