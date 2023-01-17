<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Api\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\MerchantReviews;
use App\Http\Resources\MerchantReviewsResource;
use App\Http\Resources\ProductShortResource;
use App\Http\Resources\ReviewsResource;
use App\OrderDetail;
use App\ProductPurchase;
use App\Review;
use App\Vendor;
use App\ViewProduct;
use Illuminate\Http\Request;

class SellerController extends Api
{
    public function merchant_detail(Request $request){
        $vendor_id = $request->vendor_id;
        $vendor = Vendor::with('user')->with('province')->where('slug', $vendor_id)->first();
        $reviews = Review::select('reviews.rating as rating')->join('products', 'products.id', 'reviews.product_id')
                    ->where('products.vendor_id', $vendor->id);

        // $purchases = ProductPurchase::join('products', 'products.id', 'product_purchases.product_id')
        //                 ->where('products.vendor_id', $vendor_id)->sum('total');
        $purchases = $this->purchases($vendor->id);

        $sum = $reviews->sum('reviews.rating');
        $count = $reviews->get() ? $reviews->count() : 1;
        if($sum != 0 && $count != 0){
            $avg = $sum / $count;
        } else {
            $avg = 0;
        }
        
        $data = [
            'vendor' => $vendor,
            'review' => [
                'total_review' => $count,
                'rating' => sprintf("%.1f", $avg),
            ],
            'purchase' => $purchases
        ];
        return $this->successResponse($data);
    }

    public function merchant_products(Request $request){ 
        $vendor_id = $request->vendor_id;
        $vendor = Vendor::with('city')->where('slug', $vendor_id)->first();
        //print_r($vendor->toJson());
        $merchant_product = ViewProduct::select('products.id as product_id','products.name','products.slug', 'products.category_id')
                ->with('category')
                ->join('products', 'view_products.product_id', 'products.id')
                ->where('products.vendor_id', $vendor->id)
                ->where('product_status' , 'active')
                ->where('product_details_status' , 'active');

        if ($request->filter != '') {
            if ($request->filter == 'low') {
                $merchant_product->orderBy('face_price', 'asc');
            } elseif ($request->filter == 'high') {
                $merchant_product->orderBy('face_price', 'desc');
            }
        }
        $merchant_product = $merchant_product->groupBy('products.id','products.name','products.slug','products.category_id', 'face_price');
        
        $reviews = Review::select('reviews.rating as rating')->join('products', 'products.id', 'reviews.product_id')
        ->where('products.vendor_id', $vendor->id);

        // $purchases = ProductPurchase::join('products', 'products.id', 'product_purchases.product_id')
        // ->where('products.vendor_id', $vendor_id)->sum('total');
        $purchases = $this->purchases($vendor->id);

        $sum = $reviews->sum('reviews.rating');
        $count = $reviews->get() ? $reviews->count() : 1;
        if($sum != 0 && $count != 0){
        $avg = $sum / $count;
        } else {
        $avg = 0;
        }
        
        $data = [
            'products'=>ProductShortResource::collection($merchant_product->get()),
            'vendor' => $vendor,
            'review' => [
                'total_review' => $count,
                'rating' => sprintf("%.1f", $avg),
            ],
            'purchase' => $purchases
        ];
        return $this->successResponse($data);
    }

    public function merchant_reviews(Request $request){
        
        $vendor_id = $request->vendor_id;
        $vendor = Vendor::where('slug', $vendor_id)->where('status' , 'active')->first();
        $reviews = Review::select('products.name as product_name', 'reviews.rating as rating', 'reviews.review as review',
                    'reviews.id as id', 'reviews.product_id as product_id', 'reviews.created_at as date', 'reviews.user_id')
                    ->join('products', 'products.id', 'reviews.product_id')
                    ->where('products.vendor_id', $vendor->id);

        if($request->filter != ''){
            if($request->filter == 'new'){
                $reviews->orderBy('reviews.created_at', 'desc');
            } elseif ($request->filter == 'old') {
                $reviews->orderBy('reviews.created_at', 'asc');
            } else {
                $reviews->orderBy('reviews.created_at', 'desc');
            }
        }

        $response['reviews'] = MerchantReviewsResource::collection($reviews->get());

        return $this->successResponse($response);
    }

    public function purchases($vendor_id) {
        $purchases = OrderDetail::select('quantity')
                        ->join('order_detail_products', 'order_details.id', 'order_detail_products.order_detail_id')
                        ->join('product_details', 'order_detail_products.product_detail_id', 'product_details.id')
                        ->whereIn('order_details.status', [5, 12])
                        ->where('order_details.vendor_id', $vendor_id)
                        ->sum('quantity');
        return $purchases;
    }
}
