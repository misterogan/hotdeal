<?php

namespace App\Http\Resources;

use App\Helpers\ProductUtils;
use App\Helpers\Utils;
use App\Product;
use App\ProductDetail;
use App\ProductGallery;
use App\PromotionFreeShipment;
use App\RejekiNomplokProduct;
use App\Review;
use App\ViewProduct;
use App\Wishlist;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ProductRecomendationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
       // return $this->data;
        return [
            'product_id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'vendor_id ' => $this->vendor_id,
            'vendor_name ' => $this->vendor_name,
            'vendors' => $this->vendor($this->id),  
            'discount_value'=>$this->value_discount($this->id),
            'face_price'=>floatval($this->face_price),
            'label_face_price'=>$this->label_pricing($this->id),
            'galleries' => $this->get_image($this->id),
            'video' => $this->get_video($this->id),
            'review' => $this->get_review($this->id),
            'rating' => $this->rating($this->id),
            'in_wish_list' => $this->wishlist($this->id),
            'price_before_discount' => $this->label_amount($this->id),
            'shipping_fee_discount' => $this->shipping_fee_discount,
            'having_variant' => $this->check_variant($this->id),
            'is_wish_list' => $this->is_wishlist($this->id),
            'in_rejeki_nomplok' => $this->rejeki($this->id),
        ];
    }
    private function rejeki($id){
        $product = RejekiNomplokProduct::select('id')->where('product_id' , $id)->where('status' , 'active')->first();
        if($product){
            return true;
        }
        return false;
    }

    private function wishlist($product_id){
        if(!Auth::check()){
            return false;
        }
        $wish = Wishlist::where('status' ,'active')->where('product_id',$product_id)->where('user_id', Auth::user()->id)->exists();
        return $wish;

    }

    private function get_image
    ($product_id){
        $galleries = ProductGallery::select('type','link')->where('product_id',$product_id)->where('status' ,'active')->where('type','1')->first();
        if($galleries){
            return $galleries->link;
        }else{
            return "";
        }

    }
    private function vendor($product_id){
        $vendor = Product::select('vendor_id')->where('id' , $product_id)->with('vendor')->first();
        $vendor->vendor->province;
        $vendor->vendor->city;
        return $vendor;
    }

    private function get_video
    ($product_id){
        $galleries = ProductGallery::select('type','link')->where('product_id',$product_id)->where('type','2')->first();
        if($galleries){
            return $galleries->link;
        }else{
            return "";
        }

    }

    private function rating($product_id){
        $review = Review::select('user_id','rating','review')->where('product_id',$product_id)->where('status','=','active')->get();
        $review_count = Review::where('product_id',$product_id)->where('status','=','active')->get();
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

        return number_format($res , 1);
        
    }
    private function get_review($product_id){
        return Review::select('user_id','rating','review')->where('product_id',$product_id)->where('status','=','active')->count();
    }

    private function label_pricing($product_id){
        //return $product_id;
        $min_price = ViewProduct::where('product_id',$product_id)->where('product_status' , 'active')->where('product_details_status' , 'active')->min('face_price');
        $max_price = ViewProduct::where('product_id',$product_id)->where('product_status' , 'active')->where('product_details_status' , 'active')->max('face_price');
        if($min_price == $max_price){
            return Utils::currency_convert($min_price);
        }else{
            return Utils::currency_convert($min_price)." - ".Utils::currency_convert($max_price);
        }
    }
    private function label_amount($product_id){
        $admin_fee = ViewProduct::where('product_id',$product_id)->first();
        if($admin_fee){
            $min_price = ViewProduct::where('product_id',$product_id)->where('product_status' , 'active')->where('product_details_status' , 'active')->min('price');
            $max_price = ViewProduct::where('product_id',$product_id)->where('product_status' , 'active')->where('product_details_status' , 'active')->max('price');
            $min_price = $min_price + $admin_fee->admin_fee;
            $max_price = $max_price + $admin_fee->admin_fee;
    
    
            if($min_price == $max_price){
                return Utils::currency_convert($min_price);
            }else{
                return Utils::currency_convert($min_price)."-".Utils::currency_convert($max_price);
            }
        }
    }
    // private function label_amount($product_id){
    //     $admin_fee = ViewProduct::where('product_id',$product_id)->where('product_status' , 'active')->where('product_details_status' , 'active')->first();
    //     $min_price = ViewProduct::where('product_id',$product_id)->where('product_status' , 'active')->where('product_details_status' , 'active')->min('price');
    //     $max_price = ViewProduct::where('product_id',$product_id)->where('product_status' , 'active')->where('product_details_status' , 'active')->max('price');
    //     $min_price = $min_price + $admin_fee->admin_fee;
    //     $max_price = $max_price + $admin_fee->admin_fee;


    //     if($min_price == $max_price){
    //         return Utils::currency_convert($min_price);
    //     }else{
    //         return Utils::currency_convert($min_price)."-".Utils::currency_convert($max_price);
    //     }
    // }

    private function is_wishlist($product_id){
        $whislist = Wishlist::where('product_id',$product_id)->first();
        if($whislist){
            return true;
        }else{
            return false;
        }
    }

    private function check_variant($product_id){
        $variant = ProductDetail::where('product_id', $product_id)->where('status', 'active')->select('variant_key_1')->count();
        if ($variant > 1){
            return true;
        } else {
            $produts = ViewProduct::select('product_detail_id')->where('product_id',$product_id)->where('product_status', 'active')->where('product_details_status', 'active')->first();
            if($produts){
                return $produts->product_detail_id;
            } 
            return true;
        }
    }

    private function value_discount($product_id){
        $discount = ViewProduct::where('product_id', $product_id)->first();
        if($discount) {
            $value = $discount->value_discount;
        } else {
            $value = 0;
        }
        return $value;
    }
}
