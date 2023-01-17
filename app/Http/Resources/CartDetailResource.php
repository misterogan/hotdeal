<?php

namespace App\Http\Resources;

use App\Cart;
use App\Cities;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'vendor_id' => $this->vendor_id,
            'vendor_name' => $this->name,
            'vendor_image' => $this->image,
            //'city_id' => $this->city_id,
            'vendor_city' => $this->get_city($this->city_id),
            'products' => $this->get_products($this->vendor_id),
            'vendor_checkout' => $this->checkout($this->vendor_id)
        ];
    }

    private function get_city($city_id) {
        $city = Cities::where('api_id', $city_id)->pluck('name')->first();
        return $city;
    }

    private function checkout($id) {
        $user_id = Auth::user()->id;

        $checkout = Cart::leftJoin('product_details' ,'cart.product_details_id' , 'product_details.id')
                ->leftJoin('products', 'product_details.product_id', 'products.id')
                ->leftJoin('vendors', 'products.vendor_id', 'vendors.id')
                ->with('product_detail')
                ->with('view_product')
                ->where('cart.user_id',$user_id)
                ->where('cart.status' ,'active')
                ->where('products.status', 'active')
                ->where('vendors.id', $id)
                ->whereNotNull('product_details.id')
                ->where('in_checkout', true)
                ->count();
        
        $total = Cart::leftJoin('product_details' ,'cart.product_details_id' , 'product_details.id')
            ->leftJoin('products', 'product_details.product_id', 'products.id')
            ->leftJoin('vendors', 'products.vendor_id', 'vendors.id')
            ->with('product_detail')
            ->with('view_product')
            ->where('cart.user_id',$user_id)
            ->where('cart.status' ,'active')
            ->where('products.status', 'active')
            ->where('vendors.id', $id)
            ->whereNotNull('product_details.id')
            ->count();

        if($checkout == $total){
            return true;
        }
        
        return false;
    }

    private function get_products($id) {
        $user_id = Auth::user()->id;

        $cart = Cart::leftJoin('product_details' ,'cart.product_details_id' , 'product_details.id')
                ->leftJoin('products', 'product_details.product_id', 'products.id')
                ->leftJoin('vendors', 'products.vendor_id', 'vendors.id')
                ->with('product_detail')
                ->with('view_product')
                ->where('cart.user_id',$user_id)
                ->where('cart.status' ,'active')
                ->where('products.status', 'active')
                ->where('vendors.id', $id)
                ->whereNotNull('product_details.id')
                ->orderBy('cart.in_checkout', 'desc')
                ->orderBy('cart.updated_at', 'desc')
                ->get();

        
        foreach ($cart as $value) {
            if($value->quantity > $value->stock){
                $value->in_checkout = false;
                if($value->save()){
                    $out_stock = Cart::where('user_id', $user_id)->where('product_details_id', $value->product_details_id)->first();
                    $out_stock->in_checkout = false;
                    $out_stock->updated_at = $out_stock->updated_at->subMinutes(2);
                    $out_stock->save();
                }
            }
        }
                

        $data = CartResource::collection($cart);
        return $data;
    }
}
