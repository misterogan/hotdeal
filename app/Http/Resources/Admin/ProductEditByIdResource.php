<?php

namespace App\Http\Resources\Admin;
use App\Helpers\Utils;
use App\ProductDetail;
use App\ProductGallery;
use App\PromotionDiscountProduct;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductEditByIdResource extends JsonResource
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
            'galleries' => $this->primary_image,
            'image_by_id' => $this->images_by_detail_id($this->id),
            'dimension'=>$this->dimension,
            'status' => $this->status,
            'video' => $this->video,
            'main_photo' => $this->main_photo,
            'is_have_variant' => count($this->detail) > 1 ? 'true' : 'false',
            'sku' => $this->sku,
            'variant' => $this->format_variant($this->details)
        ];
    }
   // public function is_ha

    private function images_by_detail_id($id){
        return ProductGallery::select('product_details.variant_value_1' , 'product_galleries.link')->join('product_details' ,'product_details.id' ,'product_galleries.product_detail_id')
        ->where('product_galleries.product_id' , $id)
        ->where('product_galleries.status' ,'!=', 'inactive')
        ->get();
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
    private function format_variant($data){
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
                if($v['variant_key_1'] != null){
                    $variant_pic = ProductGallery::where('status', 'active')->where('product_detail_id', $v['id'])->pluck('link')->first();
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
                // if($v['variant_key_2'] != null){
                //     $variant['variant_2']['label'] = $v['variant_key_2'];
                //     $variant['variant_2'][$v['variant_key_2']][$v['variant_value_2']][] = [
                //         'price' => $v['price'],
                //         'stock' => $v['stock'],
                //         'option' => $v['variant_value_2']
                //     ];
                // }
                
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
