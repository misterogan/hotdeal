<?php

namespace App\Http\Resources;
use App\Helpers\Utils;
use App\ProductDetail;
use App\ProductGallery;
use App\PromotionDiscountProduct;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductEditBySlugResource extends JsonResource
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
            'category' => $this->category,
            'brand_id'=>$this->brand_id,
            'brand'=>$this->brand,
            'description'=>$this->description,
            'details' => $this->detail,
            'details_variant' => $this->format_details($this->detail),
            'label_admin_fee'=>Utils::currency_convert($this->admin_fee),
            'weight'=>$this->weight,
            'galleries' => $this->galleries($this->id),
            'image_by_id' => $this->images_by_detail_id($this->id),
            'dimension'=>$this->dimension,
            'status' => $this->status,
            'video' => $this->video($this->id),
            'main_photo' => $this->main_photo($this->id),
            'is_have_variant' => count($this->detail) > 1 ? true : false,
            'sku' => $this->sku,
            'variant' => $this->format_variant($this->details, $this->id)
        ];
    }

    private function images_by_detail_id($id){
        return ProductGallery::select('product_details.variant_value_1' , 'product_galleries.link')->join('product_details' ,'product_details.id' ,'product_galleries.product_detail_id')
        ->where('product_galleries.product_id' , $id)
        ->where('product_galleries.status' ,'!=', 'inactive')
        ->get();
    }

    private function main_photo($id){
        return ProductGallery::where('product_id', $id)->where('is_primary', true)->pluck('link')->first();
    }

    private function galleries($id){
        return ProductGallery::where('product_id', $id)->where('type', 1)->where('status', 'active')->where('is_primary', false)->where('product_variant_image', null)->get();
    }

    private function video($id){
        return ProductGallery::where('product_id', $id)->where('type', 2)->where('status', 'active')->pluck('link')->first();
    }
    
    private function format_details($data){
        $result = [];
        foreach($data as $item){
            $result[$item->variant_value_1.'_'.$item->variant_value_2] = [
                'price' => $item->price,
                'quantity' => $item->stock,
                'sku' => $item->child_sku
            ];
        }
        return $result;
    }
    private function format_variant($data, $id){
        $variant = [
            'variant_1' => [
                'label' => '',
                'variant' => []
            ],
            'variant_2' => [
                'label' => '',
                'variant' => []
            ]
        ];
        $v_v1 = [];
        $v_v2 = [];
        if(count($data) > 0){
            foreach($data as $k=>$v){
                if($v->status == 'active'){
                    if($v['variant_key_1'] != null){
                        $variant_pic = ProductGallery::where('status', 'active')->where('product_id', $id)->where('product_variant_image', $v['variant_value_1'])->pluck('link')->first();
                        $variant['variant_1']['label'] = $v['variant_key_1'];
                        
                        if(!in_array( $v['variant_value_1'], $v_v1)){
                            $v_v1[] = $v['variant_value_1'];
                            $variant['variant_1']['variant'][] = [
                                        'price' => $v['price'],
                                        'stock' => $v['stock'],
                                        'option' => $v['variant_value_1'],
                                        'picture' => $variant_pic,
                                        'product_detail_id' => $v['id']
                            ];
                        }
                        
                    }
               

                    if($v['variant_key_2'] != null){
                        $variant['variant_2']['label'] = $v['variant_key_2'];
                        if(!in_array( $v['variant_value_2'], $v_v2)){
                            $v_v2[] = $v['variant_value_2'];
                            $variant['variant_2']['variant'][] = [
                                'price' => $v['price'],
                                'stock' => $v['stock'],
                                'option' => $v['variant_value_2'],
                            ];
                        }
                    }
                }
            }
        }
        return $variant;
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
                if($item->product_detail_id == 0){
                    $items[] = [
                        'detail' => $item->product_detail_id,
                        'type' => $item->type == 1 ? 'image' : 'video',
                        'url' => $item->link,
                    ];
                }
                
            }
        }
        return $items;
    }



}
