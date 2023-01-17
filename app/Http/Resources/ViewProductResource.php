<?php

namespace App\Http\Resources;
use App\Helpers\Utils;
use App\ProductDetail;
use App\ProductGallery;
use App\PromotionDiscount;
use App\PromotionDiscountProduct;
use App\RejekiNomplokProduct;
use App\ViewProduct;
use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\Collection;

class ViewProductResource extends JsonResource
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
            'stock' => $this->stock,
            'label_price' => Utils::currency_convert($this->price + $this->product->admin_fee),
            'label_face_price' => Utils::currency_convert($this->face_price),
            'price' => $this->price,
            'face_price' => $this->face_price,
            'variant_k_1' => strlen($this->variant_key_1) > 0 ? $this->variant_key_1 : "",
            'variant_v_1' => $this->variant_value_1,
            'variant_k_2' => strlen($this->variant_key_2) > 0 ? $this->variant_key_2 : "",
            'variant_v_2' => $this->variant_value_2,
            'product_gallery' =>  $this->get_image($this->product_detail_id),
            'status' => $this->status,
            'product' => new ProductsResource($this->product),
            'promotion' => $this->get_discount($this->product_id,$this->price),
            'thubmnail' => $this->thumbnail($this->product_detail_id , $this->product_id),
            'in_rejeki_nomplok' => $this->rejeki($this->product_id)
        ];
    }

    private function get_discount($id,$price){
        $discount = PromotionDiscountProduct::select('promotion_discount_products.value_discount as value_discount')
            ->leftJoin('promotion_discounts' , 'promotion_discounts.id' , 'promotion_discount_products.promotion_discounts_id')
            ->where('promotion_discounts.start_date', '<' , Utils::now())
            ->where('promotion_discounts.end_date', '>' , Utils::now())
            ->where('promotion_discount_products.product_id' , $id)
            ->where('promotion_discounts.status' , 'active')
            ->first();

        $product = ViewProduct::where('product_id', $id)
                    ->where('product_status', 'active')
                    ->where('product_details_status', 'active')
                    ->where('promotion_status', 'active')
                    ->where('discount_start', '<', Utils::now())
                    ->where('discount_end', '>', Utils::now());

        if($discount && $product){
            $value = $product->first()->value_discount;
            $nominal = $product->max('price');
            return ['value' => $value , 'nominal' => $nominal];
        }else{
            return ['value' => 0 , 'nominal' => 0];
        }

    }

    private function get_image($product_detail_id){
        $galleries = ProductGallery::select('type','link')->where('product_detail_id',$product_detail_id)->get();
        return $galleries;
    }

    private function thumbnail($product_detail_id , $product_id){
        $thumbnail = ProductGallery::select('type','link')
            ->where('product_detail_id',$product_detail_id)->where('type' , '1')
            ->first();
        if($thumbnail){
            return $thumbnail;
        }

        $thumbnail = ProductGallery::select('type','link')
            ->where('type' , '1')
            ->where('product_id' , $product_id)
            ->first();
            return $thumbnail;
    }
    private function rejeki($id){
        $product = RejekiNomplokProduct::select('id')->where('product_id' , $id)->where('status' , 'active')->first();
        if($product){
            return true;
        }
        return false;
    }
}
