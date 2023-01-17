<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Helpers\RaffleTicket;
use App\Helpers\Utils;
use App\HighlightProduct;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryFilterResource;
use App\Http\Resources\ProductLocationResource;
use App\Http\Resources\ProductShortResource;
use App\Product;
use App\Province;
use App\UserSearchLog;
use App\Vendor;
use App\ViewProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SearchController extends Api
{
    /**
     * @OA\POST(
     * path="/api/search/get",
     * summary="Search",
     * description="get result search",
     * operationId="get result search",
     * tags={"Search"},
     *security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="keyword",
     *          required=true,
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
    public function get(Request $request){
        // dd($request->all());
        $keyword = strtolower($request->keyword);
        if ($keyword == 'undefined') {
            $keyword = '';
        }

        $range = ViewProduct::select('face_price');
        $range->where('product_status' , 'active');
        $range->where('product_details_status' , 'active');

        $product = ViewProduct::select('products.id as product_id','products.name','products.slug', 'products.category_id')
                ->with('category')
                ->join('products', 'view_products.product_id', 'products.id');
        $product->where('product_status' , 'active');
        $product->where('product_details_status' , 'active');
        
        if(strlen($keyword) > 0){
            $product->where(
                function ($query) use ($keyword) {
                    $query->where(DB::raw('lower(view_products.name)'), 'like', '%' . strtolower($keyword) . '%')
                          ->orWhere(DB::raw('lower(view_products.vendor_name)'), 'like', '%' . strtolower($keyword) . '%')
                          ->orWhere(DB::raw('lower(view_products.category)'), 'like', '%' . strtolower($keyword) . '%');
                });

            $range->where(
                function ($query) use ($keyword) {
                    $query->where(DB::raw('lower(view_products.name)'), 'like', '%' . strtolower($keyword) . '%')
                            ->orWhere(DB::raw('lower(view_products.vendor_name)'), 'like', '%' . strtolower($keyword) . '%')
                            ->orWhere(DB::raw('lower(view_products.category)'), 'like', '%' . strtolower($keyword) . '%');
                });
        }
        $product->groupBy('products.id','products.name','products.slug','products.category_id');

        $location = Province::select( 'provinces.id as province_id', 'provinces.name as province_name', DB::raw('count(*) as total'))
                    ->leftJoin('vendors', 'provinces.id', 'vendors.province_id')
                    ->leftJoin('products',function($join){
                        $join->on('vendors.id', '=', 'products.vendor_id');
                        $join->where('products.status', '=', 'active');
                    })
                    ->leftJoin('categories', 'products.category_id', 'categories.id')
                    ->where('products.status', 'active')
                    ->where(DB::raw('lower(products.name)'), 'like', '%' . strtolower($keyword) . '%');
                    $location->orWhere(DB::raw('lower(categories.category)'), 'like', '%' . strtolower($keyword) . '%');
                    $location->orWhere(DB::raw('lower(vendors.name)'), 'like', '%' . strtolower($keyword) . '%');
                    $location->groupBy('provinces.id');

        $user = $this->user();
        Utils::set_memcache('keycache', $keyword);
        if ($user != null) {
            if (count($product->get()) > 0) {
                UserSearchLog::create([
                    'user_id' => $user->id,
                    'keyword' => $keyword,
                ]);
            }
        }
        // dd($product->distinct('category_id')->get());
        $response['max'] = (int)($range->max('face_price'));
        $response['min'] = (int)($range->min('face_price'));
        $response['total_record'] = $product->get()->count();
        $response['total_page'] = ceil($response['total_record'] / 50);
        $response['current_page'] = isset($request->page) ? $request->page : 1;
        $response['product'] = ProductShortResource::collection($product->paginate(50));
        $response['location'] = ProductLocationResource::collection($location->get());
        $response['category'] = CategoryFilterResource::collection($product->distinct('category_id')->get());
        return $this->successResponse($response);
    }

    /**
     * @OA\POST(
     * path="/api/product/bundling",
     * summary="Search",
     * description="Product Bundling",
     * operationId="Product Bundling",
     * tags={"Bundling"},
     *security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="keyword",
     *          required=true,
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
    public function bundling(Request $request){
        $category = new RaffleTicket();
        $category = $category->category_of_raffle_ticket();
        $keyword = strtolower($request->keyword);
        $range = ViewProduct::select('face_price');
        $range->where('product_status' , 'active');
        $range->where('product_details_status' , 'active');
        $range->whereIn('category_id' , $category);
        
        $product = ViewProduct::select('product_id','name','slug','value_discount','category_id');
        $product->where('product_status' , 'active');
        $product->where('product_details_status' , 'active');
        
        if(strlen($keyword) > 0){
            $product->where(
                function ($query) use ($keyword) {
                    $query->where(DB::raw('lower(view_products.name)'), 'like', '%' . strtolower($keyword) . '%')
                          ->orWhere(DB::raw('lower(view_products.vendor_name)'), 'like', '%' . strtolower($keyword) . '%')
                          ->orWhere(DB::raw('lower(view_products.category)'), 'like', '%' . strtolower($keyword) . '%');
                });
            
            $range->where(
                function ($query) use ($keyword) {
                    $query->where(DB::raw('lower(view_products.name)'), 'like', '%' . strtolower($keyword) . '%')
                            ->orWhere(DB::raw('lower(view_products.vendor_name)'), 'like', '%' . strtolower($keyword) . '%')
                            ->orWhere(DB::raw('lower(view_products.category)'), 'like', '%' . strtolower($keyword) . '%');
                });
        }
        $product->whereIn('category_id' , $category);
        $product->groupBy('product_id','name','slug','value_discount','category_id');

        $product_location = Vendor::select('provinces.id as province_id','provinces.name as province_name', DB::raw('count(*) as total'))
            ->leftJoin('provinces',function($join){
            $join->on('vendors.province_id', '=', 'provinces.id');
        })->leftJoin('view_products',function($join) use ($category){
                $join->on('vendors.id', '=', 'view_products.vendor_id');
                $join->whereIn('view_products.category_id',$category);
                $join->where('view_products.product_status' , 'active');
                $join->where('view_products.product_details_status' , 'active');
                $join->distinct();
            }
        )
        ->where(DB::raw('lower(view_products.name)'), 'like', '%' . strtolower($keyword) . '%');
        $product_location->orWhere(DB::raw('lower(view_products.vendor_name)'), 'like', '%' . strtolower($keyword) . '%');
        $product_location->orWhere(DB::raw('lower(view_products.category)'), 'like', '%' . strtolower($keyword) . '%');
        $product_location->where('view_products.product_status' , 'active');
        $product_location->where('view_products.product_details_status' , 'active');
       
        $product_location->groupBy('provinces.id','provinces.name');

        $user = $this->user();
        Utils::set_memcache('keycache', $keyword);
        if ($user != null) {
            if (count($product->get()) > 0) {
                UserSearchLog::create([
                    'user_id' => $user->id,
                    'keyword' => $keyword,
                ]);
            }
        }
        $response['max'] = (int)($range->max('face_price'));
        $response['min'] = (int)($range->min('face_price'));
        $response['total_record'] = $product->get()->count();
        $response['total_page'] = ceil($response['total_record'] / 50);
        $response['current_page'] = isset($request->page) ? $request->page : 1;
        $response['product'] = ProductShortResource::collection($product->paginate(50));
        $response['location'] = ProductLocationResource::collection($product_location->get());
        $response['category'] = CategoryFilterResource::collection($product->distinct('category_id')->get());
        return $this->successResponse($response);
    }

    /**
     * @OA\POST(
     * path="/api/product/categories",
     * summary="Search",
     * description="Product by Categories",
     * operationId="Product by Categories",
     * tags={"Bundling"},
     *security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="keyword",
     *          required=true,
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
    public function product_categories(Request $request){
        $keyword = strtolower($request->keyword);
        $categories = Category::where('parent_id' , $request->category)->pluck('id');
        $range = ViewProduct::select('face_price');
        $range->where('product_status' , 'active');
        $range->where('product_details_status' , 'active');
        
        $product = ViewProduct::select('product_id','name','slug','value_discount','category_id');
        $product->where('product_status' , 'active');
        $product->where('product_details_status' , 'active');
        
        if(strlen($keyword) > 0){
            $product->where(
                function ($query) use ($keyword) {
                    $query->where(DB::raw('lower(view_products.name)'), 'like', '%' . strtolower($keyword) . '%')
                          ->orWhere(DB::raw('lower(view_products.vendor_name)'), 'like', '%' . strtolower($keyword) . '%')
                          ->orWhere(DB::raw('lower(view_products.category)'), 'like', '%' . strtolower($keyword) . '%');
                });
            
            $range->where(
                function ($query) use ($keyword) {
                    $query->where(DB::raw('lower(view_products.name)'), 'like', '%' . strtolower($keyword) . '%')
                            ->orWhere(DB::raw('lower(view_products.vendor_name)'), 'like', '%' . strtolower($keyword) . '%')
                            ->orWhere(DB::raw('lower(view_products.category)'), 'like', '%' . strtolower($keyword) . '%');
                });
        }
        //$product->where('category_id' ,  $request->category);
        $product->groupBy('product_id','name','slug','value_discount','category_id');
        if($categories){
            $range->whereIn('category_id' ,$categories);    
            $product->whereIn('category_id' ,  $categories);
        }
        $product_location = Vendor::select('provinces.id as province_id','provinces.name as province_name', DB::raw('count(*) as total'))
            ->leftJoin('provinces',function($join){
            $join->on('vendors.province_id', '=', 'provinces.id');
        })->leftJoin('view_products',function($join) use ($categories){
                $join->on('vendors.id', '=', 'view_products.vendor_id');
                $join->whereIn('view_products.category_id', $categories);
                $join->where('view_products.product_status' , 'active');
                $join->where('view_products.product_details_status' , 'active');
                $join->distinct();
            }
        )
        ->where(DB::raw('lower(view_products.name)'), 'like', '%' . strtolower($keyword) . '%');
        $product_location->orWhere(DB::raw('lower(view_products.vendor_name)'), 'like', '%' . strtolower($keyword) . '%');
        $product_location->orWhere(DB::raw('lower(view_products.category)'), 'like', '%' . strtolower($keyword) . '%');
        $product_location->where('view_products.product_status' , 'active');
        $product_location->where('view_products.product_details_status' , 'active');
       
        $product_location->groupBy('provinces.id','provinces.name');

        $user = $this->user();
        Utils::set_memcache('keycache', $keyword);
        if ($user != null) {
            if (count($product->get()) > 0) {
                UserSearchLog::create([
                    'user_id' => $user->id,
                    'keyword' => $keyword,
                ]);
            }
        }
        $response['max'] = (int)($range->max('face_price'));
        $response['min'] = (int)($range->min('face_price'));
        $response['total_record'] = $product->get()->count();
        $response['total_page'] = ceil($response['total_record'] / 50);
        $response['current_page'] = isset($request->page) ? $request->page : 1;
        $response['product'] = ProductShortResource::collection($product->paginate(50));
        $response['location'] = ProductLocationResource::collection($product_location->get());
        $response['category'] = CategoryFilterResource::collection($product->distinct('category_id')->get());
        return $this->successResponse($response);
    }

    /**
     * @OA\POST(
     * path="/api/product/bundling/random",
     * summary="Search",
     * description="Product Bundling Random",
     * operationId="Product Bundling Random",
     * tags={"Bundling"},
     *security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="keyword",
     *          required=true,
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
    
    public function bundling_random(Request $request){
        $category = new RaffleTicket();
        $category = $category->category_of_raffle_ticket();
        $keyword = strtolower($request->keyword);
        $range = ViewProduct::select('face_price');
        $range->where('product_status' , 'active');
        $range->where('product_details_status' , 'active');

        $product = ViewProduct::select('product_id','name','slug','value_discount','category_id');
        $product->where('product_status' , 'active');
        $product->where('product_details_status' , 'active');

        $product->whereIn('category_id' , $category);
        $product->groupBy('product_id','name','slug','value_discount','category_id');
        $product = ProductShortResource::collection($product->limit(4)->get());
        return $this->successResponse($product);
    }


    /**
     * @OA\Get(
     * path="/api/search/get/option",
     * summary="Search",
     * description="get search log",
     * operationId="get search log",
     * tags={"Search"},
     * security={ {"sanctum": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(

     *        )
     *     )
     * )
     */

    public function get_option(Request $request){
        if(!Auth::check()){
            return $this->successResponse([]);
        }
        $user = $this->user();
        $search = UserSearchLog::Select('keyword')
            ->where('keyword','<>','')
            ->where('user_id',$user->id)->groupBy('keyword')->offset(0)->limit(10)->get();
        $response['options'] = $search;
        return $this->successResponse($response);
    }

    /**
     * @OA\Post(
     * path="/api/search/recommendation/get",
     * summary="Search",
     * description="get result search recommendation",
     * operationId="get result search recommendation",
     * tags={"Search"},
     *security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="search",
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
    public function get_search_recommendations(Request $request) {
        $user = $this->user();

        if ($user != null) {
            $previous_searches = UserSearchLog::where('user_id', $user->id)->orderByDesc('created_at')->limit(3)->get();

            if ($previous_searches->isEmpty()) {
                $previous_searches = [];
            }
        } else {
            $previous_searches = [];
        }

        $popular_products = HighlightProduct::where('status', 'active')
                            ->with(['products' => function($query){
                                $query->where('status', 'active');
                            }])
                            ->with('image')
                            ->inRandomOrder()
                            ->groupBy('product_id', 'highlight_products.id')
                            ->limit(3)->get();

        if ($popular_products->isEmpty()) {
            $popular_products = [];
        }

        // $result['recommended_keywords'] = $keywords_suggestions;
        // $result['previous_searches'] = $previous_searches;
        $result['popular_products'] = $popular_products;

        return $this->successResponse($result);
    }

    /**
     * @OA\Post(
     * path="/api/search/product",
     * summary="Search",
     * description="get result search recommendation",
     * operationId="get result search recommendation",
     * tags={"Search"},
     *security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="search",
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
    public function get_search_product(Request $request) {
        $user = $this->user();

        if ($request->has('search')) {
            $search = $request->search;
        } else {
            $search = '';
        }

        if (strlen($search) > 0) {
            $query = Product::where('status', 'active')->where('name', 'ilike', '%'. $search . '%')->limit(3)->get();
        } else {
            $query = HighlightProduct::where('status', 'active')->with('products')->inRandomOrder()->limit(3)->get();
        }

        if (!$query->isEmpty()) {
            if (strlen($search) > 0) {
                foreach($query as $q) {
                    $keywords_suggestions[] = strtolower(strstr($q->name, ' ', true));
                }
            } else {
                foreach($query as $q) {
                    $keywords_suggestions[] = strtolower(strstr($q->products->name, ' ', true));
                }
            }
        } else {
            $keywords_suggestions = [];
        }

        $result['recommended_keywords'] = $keywords_suggestions;

        return $this->successResponse($result);
    }

     /**
     * @OA\Post(
     * path="/api/search/history/delete",
     * summary="Search",
     * description="Delete history by keyword",
     * operationId="Delete history by keyword",
     * tags={"Delete"},
     *security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="keyword",
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
    public function search_history_delete(Request $request){
        $user = $this->user();
        if(!$user){
            $this->errorResponse('Error',201);
        }
        if($request->type == 'all'){
            $delete = UserSearchLog::where('user_id' , $user->id)->delete();
            return $this->successResponse();
        }else{
            $delete = UserSearchLog::where('user_id' , $user->id)->where('keyword' , $request->keyword)->delete();
            return $this->successResponse();
        }
    }

}
