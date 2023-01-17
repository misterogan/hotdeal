<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
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
            'name' => $this->name,
            'img_url' => $this->img_url,
            'img_url_mobile' => $this->img_url_mobile,
            'video_url' => $this->video_url,
            'url' => $this->url,
            'type' => $this->type,
            'placement' => $this->placement,
            'sequence' => $this->sequence,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'new_tab' => $this->new_tab
        ];

    }
}
