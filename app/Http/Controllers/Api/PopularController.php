<?php

namespace App\Http\Controllers\Api;
use App\Product;
use App\ViewProduct;

class PopularController extends Api
{

    public function popular_product(){
        $data = Product::select('name','slug')->where('status' ,'active')->limit(3)->get();
        return $data;
    }
    public function popular_category(){
        $data = ViewProduct::select('category')->where('product_details_status' ,'active')->where('product_status' ,'active')->inRandomOrder()->limit(4)->groupBy('category')->get();
        return $data;
    }
}
