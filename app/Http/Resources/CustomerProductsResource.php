<?php

namespace App\Http\Resources;

use App\Category;
use App\Helpers\ProductUtils;
use App\Helpers\Utils;
use App\Product;
use App\ProductDetail;
use App\ProductGallery;
use App\PromotionDiscount;
use App\PromotionDiscountProduct;
use App\PromotionVoucher;
use App\PromotionVoucherProduct;
use App\RejekiNomplokProduct;
use App\Review;
use App\Vendor;
use App\ViewProduct;
use App\Wishlist;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CustomerProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'vendor' => $this->detail_vendors($this->vendor_id),
            'category_id'=>$this->category_id,
            'brand_id'=>$this->brand_id,
            'description'=>$this->description, 
            'weight'=>$this->weight,
            'galleries' => $this->format_image($this->galleries),
            'image' => $this->image,
            'video' => $this->video,
            'dimension'=>$this->dimension,
            'status ' => $this->status,
            'created_at ' => $this->created_at,
            'updated_at ' => $this->updated_at,
            'reviews' => ReviewsResource::collection($this->review),
            'detail'=> $this->format_details($this->id ,$this->detail , $this->admin_fee),
            'rating' => $this->get_rating($this->id),
            'total_review' => $this->get_count_review($this->id),
            'discount' => $this->get_discount($this->id , $this->detail),
            'promo' => $this->get_promo($this->id),
            'face_price' => $this->face_price,
            'label_face_price'=>$this->label_pricing($this->id),
            'label_amount'=>$this->label_amount($this->id),
            'having_variant' => $this->check_detail($this->id),
            'in_wish_list' => $this->wishlist($this->id),
            'in_rejeki_nomplok' => $this->rejeki($this->id),
            'in_voucher' => $this->voucher($this->id),
            'is_gratis_ongkir' => $this->gratis_ongkir(),
            'square' => $this->img_square,
            'portrait' => $this->img_portrait,
            'landscape' => $this->img_landscape,
            'sold' => $this->sold($this->id),
            'total_stock' => $this->total_stock($this->id),
            'category' => $this->category($this->id),
            'text_promo' => $this->text_promo($this->id)
        ];
        return $data;
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

    private function wishlist($product_id){
        if(!Auth::check()){
            return false;
        }
        $wish = Wishlist::where('status' ,'active')->where('product_id',$product_id)->where('user_id', Auth::user()->id)->exists();
        return $wish;

    }

    private function check_detail($product_id){
        $products_sum = ViewProduct::where('product_id',$product_id)->count();
        if ($products_sum > 1){
            return true;
        }else{
            $produts = ViewProduct::select('product_detail_id')->where('product_id',$product_id)->first();
            if($produts){
                return $produts->product_detail_id;
            }else{
                return false;
            }
            
        }
    }

    private function get_discount($id , $details){
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
            return ['value' => (int)($value) , 'nominal' => $nominal];
        }else{
            return ['value' => 0 , 'nominal' => 0];
        }
    }
    private function get_promo($id){
        return [$id];
    }

    private function format_details($id ,$details , $admin_fee){
        $discount = PromotionDiscountProduct::select('promotion_discount_products.value_discount')->
        leftJoin('promotion_discounts' , 'promotion_discounts.id' , 'promotion_discount_products.promotion_discounts_id')
        ->where('promotion_discounts.start_date', '<' , Utils::now())
        ->where('promotion_discounts.end_date', '>' , Utils::now())
        ->where('promotion_discount_products.product_id' , $id)
        ->where('promotion_discounts.status' , 'active')
        ->first();

        if(!$discount){
            $discount = 0;
        }else{
            $discount = $discount->value_discount;
        }

        $detail = [];
        $detail['variant_value_1'] = [];
        $detail['variant_value_2'] = [];
        if(count($details) < 1){
            $detail['variant_key_1'] = [];
            $detail['variant_key_2'] = [];
            $detail['variant_value_1'] = [];
            $detail['variant_value_2'] = [];
            $detail['price'] = $this->format_currency(0);
            return $detail;
        }
        if(count($details) > 1){
            $offset = count($details)-1;
            if((($details[0]->price + $admin_fee) - $discount) != (($details[$offset]->price + $admin_fee) - $discount)){
                $detail['price'] = $this->format_currency(($details[0]->price + $admin_fee) - $discount).' - '.$this->format_currency((($details[$offset]->price + $admin_fee) - $discount));
            }else{
                $detail['price'] = $this->format_currency(($details[0]->price + $admin_fee) - $discount);    
            }            
        }else{
            $detail['price'] = $this->format_currency(($details[0]->price + $admin_fee) - $discount);
        }
        foreach($details as $key=>$value){
            $detail['variant_key_1'] = $value->variant_key_1;
            $detail['variant_key_2'] = $value->variant_key_2;
            if(!in_array($value->variant_value_1 , $detail['variant_value_1'])){
                if($value->variant_key_1 != '' || $value->variant_key_1 != null){
                    $detail['variant_value_1'][] = $value->variant_value_1;
                }
            }
            if(!in_array($value->variant_value_2 , $detail['variant_value_2'])){
                if($value->variant_key_2 != '' || $value->variant_key_2 != null){
                    $detail['variant_value_2'][] = $value->variant_value_2;
                }
            }
            $prices = $value->price;
            $detail['variant_data'][str_replace(' ' , '_', $value->variant_value_1).'_'.str_replace(' ' , '_',$value->variant_value_2)] = $this->format_value($value , $admin_fee , $discount);
        }
        return $detail;
    }

    private function format_value($data, $admin_fee , $discount){
        return [
            'stock' => $data->stock,
            'price' =>($data->price + $admin_fee) - $discount,
            'image' => $data->product_galleries_id,
            'pdid' => $data->id
        ];
    }

    private function format_currency($string){
        return 'Rp '.number_format($string , 0 , '.' ,'.');
    }

    private function format_image($data){
        $items = [];
        if(count($data) > 0){
            foreach($data as $item){
                $str = str_replace(' ', '-', strtolower($item->product_variant_image));
                $string = substr($str, -1);
                $variant = $string == '-' ? substr($str, 0, -1) : $str;
                $items[] = [
                    'detail' => $item->product_detail_id,
                    'type' => $item->type == 1 ? 'image' : 'video',
                    'url' => $item->link,
                    'variant' => $item->product_variant_image == null ? 'variant-class' : $variant
                ];
            }
        }
        return $items;
    }

    private function primary_image($id){
        $image = ProductGallery::where('product_id', $id)->where('status', 'active')->where('is_primary', true)->pluck('link')->first();
        return $image;
    }

    private function get_rating($product_id){
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

        return $res;
    }

    private function get_count_review($product_id){
        $review_count = Review::where('product_id',$product_id)->where('status','=','active')->get();
        $review_count = count($review_count);
        return $review_count;
    }

    private function detail_vendors($vendor_id){
        $vendors = Vendor::select('vendors.image','vendors.slug' ,'vendors.address','vendors.id as vendor_id','vendors.name as vendor_name','vendors.user_id','provinces.name as province','cities.name as city')
            ->leftjoin('provinces', 'vendors.province_id', '=', 'provinces.id')
            ->leftjoin('cities', 'vendors.city_id', '=', 'cities.api_id')
            ->where('vendors.id',$vendor_id)->first();
        return $vendors;
    }

    private function rejeki($id){
        $product = RejekiNomplokProduct::select('id')->where('product_id' , $id)->where('status' , 'active')->first();
        if($product){
            return true;
        }
        return false;
    }

    private function voucher($id){
        $product = PromotionVoucherProduct::select('id')->where('product_id' , $id)->where('status' , 'active')->first();
        if($product){
            return true;
        }
        return false;
    }

    private function gratis_ongkir(){
        $now = Utils::now();
        $product = PromotionVoucher::where('status', 'active')
                        ->where('start_date', '<=',  $now)
                        ->where('end_date', '>', $now)
                        ->where('value_discount', '>=', '10000')
                        ->first();
        if($product){
            return true;
        }
        return false;
    }

    private function sold($id){
        $data = ProductUtils::sold_product_by_id($id);

        if($data['sold'] > 5000 && $data['sold'] <= 9999) {
            $text = "5rb+";
        } else if($data['sold'] >= 10000){
            $text = "10rb+";
        } else {
            $text = $data['sold'] != null ? $data['sold'] : 0;
        }
        return $text;
    }

    private function total_stock($id) {
        $data = ProductDetail::where('status', 'active')->where('product_id', $id)->sum('stock');
        return $data;
    }

    private function category($id) {
        $category_id = Product::where('id', $id)->pluck('category_id')->first();
        $category = Category::where('id', $category_id)->pluck('category')->first();
        return $category;
    }

    function text_promo($id) {
        $go = $this->gratis_ongkir($id);
        $voucher = $this->voucher($id);
        $rn = $this->rejeki($id);
        // $rn = true;

        $text = [];
        if($go) {
            array_push($text, 'Gratis Ongkir');
        }
        if($voucher){
            array_push($text, 'Voucher');
        }
        if($rn){
            array_push($text, 'Rejeki Nomplok');
        }
        
        if(count($text) == 2){
            $word = implode(" & ",$text);
        } else if(count($text) == 3){
            $last  = array_slice($text, -1);
            $first = join(', ', array_slice($text, 0, -1));
            $both  = array_filter(array_merge(array($first), $last), 'strlen');
            $word = join(' & ', $both);
        } else if (count($text) == 1) {
            $word = implode(" ",$text);
        } else {
            $word = null;
        }

        $data['text'] = $word;
        $data['have_promo'] = $word != null ? true : false;

        return $data;
        
    }

}
