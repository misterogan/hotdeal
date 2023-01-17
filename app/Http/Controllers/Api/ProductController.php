<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CustomerProductsResource;
use App\Http\Resources\ProductRecomendationResource;
use App\Http\Resources\ProductShortResource;
use App\Http\Resources\ProductsResource;
use App\Http\Resources\ProductSuggestionResource;
use App\OrderDetailProduct;
use App\Product;
use App\ProductDetail;
use App\ProductSuggestion;
use App\UserSearchLog;
use App\ViewProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Api
{

    /**
     * @OA\Get(
     * path="/api/product/list",
     * summary="Product List",
     * description="Product List",
     * operationId="List",
     * tags={"Product"},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(
     *        )
     *     )
     * )
     */

    public function list(Request $request)
    {
        $user = $this->user();
        if ($user) {
            // Do something what yo want
        }
        $product = Product::with('detail')->with('galleries')->with('brand')->with('category')->where('status', 'active')->orderBy('name')->get();
        $data = ProductsResource::collection($product);
        return $this->successResponse($data);
    }

    public function product_dt()
    {
        $product = Product::with('detail')->with('galleries')->with('brand')->with('category')->orderBy('name')->get();
        $meta = [
            "sort" => "asc",
            "field" => "id",
            "page" => 1,
            "pages" => 1,
            "perpage" => "10",
            "total" => 10
        ];
        $res = [
            "meta" => $meta,
            "data" => $product
        ];

        return $res;
    }

    /**
     * @OA\Post(
     * path="/api/product/detail",
     * summary="ProductList",
     * description="Product Detail",
     * operationId="ProductDeatil",
     * tags={"Product"},
     * security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="slug",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(
     *        )
     *     )
     * )
     */

    public function detail(Request $request)
    {
        // dd($request->slug);
        if ($request->slug != '') {
            $product = Product::with('detail', 'galleries', 'in_wish_list')->where('status', 'active')->where('slug', $request->slug)->first();
            if ($product) {
                $data = [
                    'product' => new CustomerProductsResource($product)
                ];
                return $this->successResponse($data);
            }
            return $this->errorResponse('Data Not Found', 201);
        } else {
            return redirect('/product');
        }
    }

    /**
     * @OA\Get(
     * path="/api/product/suggestion/get",
     * summary="product_suggestion",
     * description="get product suggestion",
     * operationId="get product suggestion",
     * tags={"Product"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(

     *        )
     *     )
     * )
     */
    public function product_suggestion_get(Request $request)
    {
        $user = $this->user();
        if ($request->slug == 'undefined' || $request->slug == null) {
            return $this->errorResponse('Not Found', 404);
        } else {
            $product = Product::where('slug', $request->slug)->where('status', 'active')->first();
            if ($product) {
                // step 1 same category and merchant
                $step_1 = Product::with('category')->where('id', '!=', $product->id)->where('category_id', $product->category_id)->where('vendor_id', $product->vendor_id)->where('status', 'active')->groupBy('id', 'name');
                $products = $step_1;
                if ($step_1->get()->count() <= 6) {
                    // step 2 same merchant
                    $step_2 = Product::with('category')->where('id', '!=', $product->id)->where('vendor_id', $product->vendor_id)->where('status', 'active')->groupBy('id', 'name')->inRandomOrder()->limit(6);
                }
                if (isset($step_2)) {
                    if ($step_2->get()->count() < 6) {
                        $union = $step_1->union($step_2);
                        $products = $union->groupBy('id', 'name');
                        if ($union->get()->count() <= 6) {
                            // step 3 same category
                            $step_3 = Product::with('category')->where('id', '!=', $product->id)->where('category_id', $product->category_id)->where('status', 'active')->groupBy('id', 'name')->inRandomOrder()->limit(6);
                            $products = $union->union($step_3);
                            $products->groupBy('id', 'name');
                        }
                    } else {
                        $products = $step_1->union($step_2);
                        $products = $products->groupBy('id', 'name');
                    }
                }
            } else {
                $products = Product::with('category')->groupBy('id', 'name')->where('status', 'active')->inRandomOrder()->limit(4);
            }
            if ($product) {
                if ($products->get()->count() < 6) {
                    $random = Product::with('category')->where('id', '!=', $product->id)->groupBy('id', 'name')->where('status', 'active')->inRandomOrder()->limit(4);
                    $products->union($random);
                }
            }
        }

        // if($user){
        //     $user_log = UserSearchLog::where('user_id',$user->id)->latest('created_at')->first();
        //     if($user_log){

        //         $keyword = strtolower($user_log->keyword);
        //         $prod = Product::with('category')->where(DB::raw('lower(name)'), 'like ', '%' . strtolower($keyword) . '%')->where('status' , 'active')->where('id', '!=', $product->id)->first();
        //         if(!$prod){
        //             goto next;
        //         }
        //         $products_suggestion = $products->where('status', 'active')->limit(4)->get();
        //         $data = [
        //             'product_suggestion'=>ProductRecomendationResource::collection($products_suggestion->shuffle()),
        //         ];
        //         return $this->successResponse($data);
        //     }
        // }
        // next:
        $products_suggestion = $products->where('status', 'active')->limit(6)->get();
        $data = [
            'product_suggestion' => ProductRecomendationResource::collection($products_suggestion->shuffle()),
        ];
        return $this->successResponse($data);
    }

    public function popular_product()
    {
        $data = Product::select('name', 'slug')->where('status', 'active')->limit(3)->get();
        return $data;
    }

    public function status_product(Request $request)
    {
        $product = Product::where('id', $request->product_id)->update([
            // 'vendor_id' => $request->vendor_id,
            'status' => $request->status,
        ]);
        return $this->successResponse($product, 'id' . $request->id);
    }

    public function get_latest_product()
    {
        // $product = Product::where('status', 'active')->orderBy('created_by', 'DESC')->limit(6)->get();
        // $data = ProductsResource::collection($product);
        $product  = Product::select('products.*')
            ->with('review')
            ->with('image')
            ->with('galleries')
            // ->with('in_wish_list')
            ->where('products.status', 'active')
            ->orderBy('products.id', 'DESC')->limit(6)->get();
        // ->where('wishlist.status','active')
        // ->where('wishlist.user_id' , $user->id)
        // ->orderby('wishlist.updated_at', 'DESC')->get();
        $data = [];
        if ($product) {
            $data = ProductRecomendationResource::collection($product);
        }
        return $this->successResponse($data);
    }
}
