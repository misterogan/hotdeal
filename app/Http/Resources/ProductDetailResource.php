<?php

namespace App\Http\Resources;
use App\Helpers\Utils;
use App\ProductDetail;
use App\ProductGallery;
use App\PromotionDiscount;
use App\PromotionDiscountProduct;
use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\Collection;

class ProductDetailResource extends JsonResource
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
            'label_price' => Utils::currency_convert($this->price),
            'price' => $this->price,
            'variant_k_1' => $this->variant_key_1,
            'variant_v_1' => $this->variant_value_1,
            'variant_k_2' => $this->variant_key_2,
            'variant_v_2' => $this->variant_value_2,
            'product_gallery' =>  $this->get_image($this->id),
            'status' => $this->status,
            'product' => new ProductsResource($this->product),
            //'promotion' => $this->get_discount($this->id,$this->price),
            'thubmnail' => $this->thumbnail($this->id),
        ];
    }

    private function get_discount($product_detail_id,$price){

        $discount_detail =  PromotionDiscountProduct::where('product_detail_id',$product_detail_id)->first();
        $discount = PromotionDiscount::select( 'promo_from', 'vendor_id',  'value_discount', 'status', 'start_date', 'end_date')->where('id',$discount_detail->promotion_discounts_id)->first();
        $discount->nominal_discount = $discount->value_discount/100 * $price;
        $discount->price_after_discount =  $price - $discount->value_discount/100 * $price ;
        $discount->label_price_after_discount =  Utils::currency_convert($price - $discount->value_discount/100 * $price) ;
        return $discount;

    }

    private function get_image($product_detail_id){
        $galleries = ProductGallery::select('type','link')->where('product_detail_id',$product_detail_id)->get();
        return $galleries;
    }

    private function thumbnail($product_detail_id){
        $thumbnail = ProductGallery::select('type','link')
            ->where('product_detail_id',$product_detail_id)->where('type' , '1')
            ->first();
        if($thumbnail){
            return $thumbnail;
        }

        $thumbnail = ProductGallery::select('type','link')
            ->where('type' , '1')
            ->first();
            return $thumbnail;
    }
}
