<?php

namespace App\Http\Resources;
use App\Helpers\Utils;
use App\ProductDetail;
use App\PromotionDiscountProduct;
use App\PromotionFreeShipment;
use App\Review;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductsResource extends JsonResource
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
//            'admin_fee'=>$this->admin_fee,
            'weight'=>$this->weight,
            'galleries' => $this->format_image($this->galleries),
            'dimension'=>$this->dimension,
            'status ' => $this->status,
            'review' => $this->get_review($this->id),
            'detail'=> $this->format_details($this->detail),
            'discount_value'=>$this->value_discount,
            'shipping_fee_discount'=>$this->shipping_fee_discount_value,
        ];
    }
    private function format_details($details){
        $detail = [];
        foreach($details as $key=>$value){
            if($value->variant_key_1 != '' || $value->variant_key_1 != null){
                if($value->variant_key_2 != '' || $value->variant_key_2 != null){
                    //$detail[$value->variant_key_1]['key'] = $value->variant_key_1;
                    //$detail[$value->variant_key_1]['value'] = $value->variant_value_1;
                    $detail[$value->variant_key_1][$value->variant_value_1][$value->variant_key_2][$value->variant_value_2][] = $this->format_value($value);
                }else{
                    $detail[$value->variant_key_1][$value->variant_value_1] = $value;
                }
            }
        }
        return $detail;
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

    private function get_shipping($product_id){
        $free_shipping  = PromotionFreeShipment::select('promo_from','minimum_payment','shipping_fee_discount','start_date','end_date')->where('product_id',$product_id)->where('status','active')->first();
        $date_now = date('Y-m-d H:i:s');
        if($free_shipping &&  $date_now >= $free_shipping->start_date && $date_now <= $free_shipping->end_date){
            return true;
        }else{
            return false;
        }
    }

    private function get_review($product_id){

        $review = Review::select('user_id','rating','review')->where('product_id',$product_id)->get();
        $review_count = Review::where('product_id',$product_id)->get();
        $review_count = count($review_count);
        $result = 0;
        $ratings_count = 0;
        $user_count = 0;
        foreach ($review as $key=> $val){
            $ratings_count= $ratings_count + $val->rating;
            $user_count = $user_count + 1;
        }
        if($review_count > 0){
            $res =  $ratings_count / $review_count ;
        }else{
            $res = 0;
        }

        return $res;
    }



}
