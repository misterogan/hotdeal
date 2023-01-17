<?php

namespace App\Http\Resources;

use App\Product;
use App\Vendor;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetail2Resource extends JsonResource
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
            'product_id'=>$this->product_id,

        ];
    }

    function get_vendor($product_id){
        $product = Product::where('id',$product_id)->first();
        $vendor_id = $product->vendor_id;
        $vendors = Vendor::select('vendors.id as vendor_id','vendors.name as vendor_name','vendors.user_id','provinces.name as province','cities.name as city')
            ->leftjoin('provinces', 'vendors.province_id', '=', 'provinces.id')
            ->leftjoin('cities', 'vendors.city_id', '=', 'cities.id')
            ->where('vendors.id',$vendor_id)->first();
        return $vendors;

    }
}
