<?php

namespace App\Http\Controllers\Api;

use App\HighlightProduct;
use App\Http\Resources\CustomerProductsResource;
use App\Http\Resources\HighlightOneResource;
use App\Http\Resources\HighlightProductsResource;
use App\Http\Resources\ProductShortResource;
use App\Http\Resources\ProductsResource;
use App\Product;
use App\ViewProduct;
use Illuminate\Http\Request;

class HighlightController extends Api
{
    /**
     * @OA\Get(
     * path="/api/highlight/get/top",
     * summary="highlight",
     * description="get tophighlight",
     * operationId="get tophighlight",
     * tags={"Highlight"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(

     *        )
     *     )
     * )
     */
    public function get_top_highlight(Request $request)
    {
        
        $top_highlight_product  = HighlightProduct::select('highlight_products.img_square','highlight_products.img_landscape','highlight_products.img_portrait','view_products.product_id','view_products.name','view_products.slug','view_products.value_discount','highlight_products.sequence', 'highlight_products.deep_link', 'highlight_products.new_tab')
        ->join('view_products' ,'view_products.product_id','highlight_products.product_id')
        ->where('highlight_products.status','active')
        ->where('highlight_products.highlight_type','1')
        ->orderBy('highlight_products.sequence','asc')
        ->groupBy('highlight_products.img_square','highlight_products.img_landscape','highlight_products.img_portrait','view_products.product_id','view_products.name','view_products.slug','view_products.value_discount','category_id','highlight_products.sequence', 'highlight_products.deep_link', 'highlight_products.new_tab')
        ->get();

        //echo json_encode($top_highlight_product);return;

        return $this->successResponse(HighlightOneResource::collection($top_highlight_product));
    }

    /**
     * @OA\Get(
     * path="/api/highlight/get/bottom",
     * summary="highlight",
     * description="get bottomhighlight",
     * operationId="get bottomhighlight",
     * tags={"Highlight"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(

     *        )
     *     )
     * )
     */
    public function get_bottom_highlight(Request $request)
    {
        $top_highlight_product  = HighlightProduct::select('highlight_products.img_square','highlight_products.img_landscape','highlight_products.img_portrait','view_products.product_id','view_products.name','view_products.slug','view_products.value_discount','highlight_products.sequence')
        ->join('view_products' ,'view_products.product_id','highlight_products.product_id')
        ->where('highlight_products.status','active')
        ->where('highlight_products.highlight_type','3')
        ->orderBy('highlight_products.sequence','desc')
        ->groupBy('highlight_products.img_square','highlight_products.img_landscape','highlight_products.img_portrait','view_products.product_id','view_products.name','view_products.slug','view_products.value_discount','category_id','highlight_products.sequence')
        ->get();
        return $this->successResponse(ProductShortResource::collection($top_highlight_product));
    }

    /**
     * @OA\Get(
     * path="/api/highlight/get/middle",
     * summary="highlight",
     * description="get middlehighlight",
     * operationId="get middlehighlight",
     * tags={"Highlight"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(

     *        )
     *     )
     * )
     */
    public function get_middle_highlight(Request $request) {
        $top_highlight_product  = HighlightProduct::select('highlight_products.img_square','highlight_products.img_landscape','highlight_products.img_portrait','view_products.product_id','view_products.name','view_products.slug','view_products.value_discount','highlight_products.sequence')
        ->join('view_products' ,'view_products.product_id','highlight_products.product_id')
        ->where('highlight_products.status','active')
        ->where('view_products.product_status','active')
        ->where('view_products.product_details_status','active')
        ->where('highlight_products.highlight_type','3')
        ->orderBy('highlight_products.sequence','ASC')
        ->groupBy('highlight_products.img_square','highlight_products.img_landscape','highlight_products.img_portrait','view_products.product_id','view_products.name','view_products.slug','view_products.value_discount','category_id','highlight_products.sequence')
        ->limit(6)
        ->get();
        $data = [];
        if($top_highlight_product){
            $data = ProductShortResource::collection($top_highlight_product);
        }

        return $this->successResponse($data);

    }

    public function get_random_product(Request $request) {
        
        $products = ViewProduct::select('products.id as product_id','products.name','products.slug', 'products.category_id')
                    ->with('category')
                    ->join('products', 'view_products.product_id', 'products.id')
                    ->where('products.status', 'active')
                    ->where('view_products.product_status', 'active')
                    ->where('view_products.product_details_status', 'active')
                    ->inRandomOrder()
                    ->groupBy('products.id','products.name','products.slug','products.category_id');
                    
        $data = [];

        if($products){
            $collection = ProductShortResource::collection($products->paginate(18));
            $data['total_page'] = 3;
            $data['current_page'] = isset($request->page) ? $request->page : 1;
            $data['products'] = $collection->chunk(6);
        }
        return $this->successResponse($data);

    }

    public function get_newest_product(Request $request) {
        
        $products = ViewProduct::select('products.id as product_id','products.name','products.slug', 'products.vendor_id')
                    ->with('category')
                    ->join('products', 'view_products.product_id', 'products.id')
                    ->where('products.status', 'active')
                    ->where('view_products.product_status', 'active')
                    ->where('view_products.product_details_status', 'active')
                    // ->distinct('products.vendor_id')
                    // ->orderBy('products.vendor_id')
                    ->orderby('products.created_at', 'DESC')
                    ->groupBy('products.id', 'products.vendor_id','products.name','products.slug');
                    
        $data = [];
        if($products){
            $data['total_page'] = 3;
            $data['current_page'] = isset($request->page) ? $request->page : 1;
            $data['products'] = ProductShortResource::collection($products->get()->unique('vendor_id')->take(6));
        }
        return $this->successResponse($data);

    }

    /**
     * @OA\Get(
     * path="/api/highlight/get/footer",
     * summary="highlight",
     * description="get footerhighlight",
     * operationId="get footerhighlight",
     * tags={"Highlight"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(

     *        )
     *     )
     * )
     */
    public function get_footer_highlight(Request $request) {
        $highlight  = HighlightProduct::select('highlight_products.img_square','highlight_products.img_landscape','highlight_products.img_portrait','view_products.product_id','view_products.name','view_products.slug','view_products.value_discount','highlight_products.sequence')
        ->join('view_products' ,'view_products.product_id','highlight_products.product_id')
        ->where('highlight_products.status','active')
        ->where('view_products.product_status','active')
        ->where('view_products.product_details_status','active')
        ->where('highlight_products.highlight_type','4')
        ->orderBy('highlight_products.sequence','ASC')
        ->groupBy('highlight_products.img_square','highlight_products.img_landscape','highlight_products.img_portrait','view_products.product_id','view_products.name','view_products.slug','view_products.value_discount','category_id','highlight_products.sequence')
        ->limit(6)
        ->get();
        $data = [];
        if($highlight){
            $data = ProductShortResource::collection($highlight);
        }

        return $this->successResponse($data);
    }

    /**
     * @OA\Get(
     * path="/api/highlight/video",
     * summary="highlight",
     * description="get videohighlight",
     * operationId="get videohighlight",
     * tags={"Highlight"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(

     *        )
     *     )
     * )
     */
    public function get_vid_highlight(Request $request)
    {
        $video  = HighlightProduct::select('highlight_products.img_square','highlight_products.img_landscape','highlight_products.img_portrait','view_products.product_id','view_products.name','view_products.slug','view_products.value_discount','highlight_products.sequence', 'highlight_products.deep_link')
        ->join('view_products' ,'view_products.product_id','highlight_products.product_id')
        ->where('highlight_products.status','active')
        ->where('view_products.product_status','active')
        ->where('view_products.product_details_status','active')
        ->where('highlight_products.highlight_type','2')
        ->orderBy('highlight_products.sequence','ASC')
        ->groupBy('highlight_products.img_square','highlight_products.img_landscape','highlight_products.img_portrait','view_products.product_id','view_products.name','view_products.slug','view_products.value_discount','category_id','highlight_products.sequence', 'highlight_products.deep_link')
        ->limit(6)
        ->get();
        $data = [];
        if($video){
            $data = ProductShortResource::collection($video);
        }

        return $this->successResponse($data);
    }

    /**
     * @OA\Get(
     * path="/api/highlight/intereted",
     * summary="highlight",
     * description="get intereted",
     * operationId="get intereted",
     * tags={"Highlight"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(

     *        )
     *     )
     * )
     */
    public function product_interested(Request $request)
    {
        $video  = HighlightProduct::select('highlight_products.img_square','highlight_products.img_landscape','highlight_products.img_portrait','view_products.product_id','view_products.name','view_products.slug','view_products.value_discount','highlight_products.sequence')
        ->join('view_products' ,'view_products.product_id','highlight_products.product_id')
        ->where('highlight_products.status','active')
        ->where('view_products.product_status','active')
        ->where('view_products.product_details_status','active')
        ->where('highlight_products.highlight_type','2')
        ->orderBy('highlight_products.sequence','ASC')
        ->groupBy('highlight_products.img_square','highlight_products.img_landscape','highlight_products.img_portrait','view_products.product_id','view_products.name','view_products.slug','view_products.value_discount','category_id','highlight_products.sequence')
        ->limit(6)
        ->get();
        $data = [];
        if($video){
            $data = ProductShortResource::collection($video);
        }

        return $this->successResponse($data);
    }


    /**
     * @OA\Get(
     * path="/api/highlight/get/wishlis",
     * summary="highlight",
     * description="Highlight WishList",
     * operationId="list",
     * tags={"highlight"},
     * security={ {"sanctum": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(
     *        )
     *     )
     * )
     */
    public function wishlist() {
        $user = $this->user();
        if(!$user){
            return $this->successResponse([]);
        }
        $wishlist = ViewProduct::select('products.id as product_id','products.name','products.slug', 'products.category_id')
                    ->with('category')
                    ->join('products', 'view_products.product_id', 'products.id')
                    ->join('wishlist', 'products.id', 'wishlist.product_id')
                    ->where('products.status', 'active')
                    ->where('wishlist.status', 'active')
                    ->where('wishlist.user_id' , $user->id)
                    ->orderby('wishlist.updated_at', 'DESC')
                    ->groupBy('products.id','products.name','products.slug','products.category_id', 'wishlist.updated_at')
                    ->limit(6)
                    ->get();
                    
        $data = [];
        if($wishlist){
            $data = ProductShortResource::collection($wishlist);
        }
        return $this->successResponse($data);
    }
}
