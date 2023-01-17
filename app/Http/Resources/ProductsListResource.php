<?php

namespace App\Http\Resources;
use App\Helpers\Utils;
use App\ProductDetail;
use App\PromotionDiscountProduct;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductsListResource extends JsonResource
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
            'slug' => $this->slug,
            'vendor_id ' => $this->vendor_id,
            'category_id'=>$this->category_id,
            'brand_id'=>$this->brand_id,
            'description'=>$this->description,
            'label_admin_fee'=>Utils::currency_convert($this->admin_fee),
            'admin_fee'=>$this->admin_fee,
            'weight'=>$this->weight,
            'galleries' => $this->format_image($this->galleries),
            'dimension'=>$this->dimension,
            'status ' => $this->status,
            'reviews' => ReviewsResource::collection($this->review),
        ];
    }
    
    private function format_value($data){
        return [
            'stock' => $data->stock,
            'price' => $data->price,
            'image' => $data->product_galleries_id
        ];
    }

    private function format_image($data){
        $items = [];
        if(count($data) > 0){
            foreach($data as $item){
                $items[] = [
                    'detail' => $item->product_detail_id,
                    'type' => $item->type == 1 ? 'image' : 'video',
                    'url' => $item->link,
                ];
            }
        }
        return $items;
    }



}
