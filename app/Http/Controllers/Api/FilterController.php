<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryFilterResource;
use App\Http\Resources\ProductShortResource;
use App\MasterStatusOrder;
use App\Product;
use App\ViewProduct;
use http\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FilterController extends Api
{
    /**
     * @OA\Post(
     * path="/api/filter/get",
     * summary="Filter",
     * description="result filter",
     * operationId="result filter",
     * tags={"Filter"},
     *security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="province_id",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="min_price",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="max_price",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="category_id",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="review",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="shipment_service_id",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="brand_id",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
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
        $filter = $request->filter;
        $keyword = '';
        if(isset($filter['keyword'])){
            $keyword = $filter['keyword'];
        }
        $product = ViewProduct::select('product_id','name','slug','value_discount','province_id', 'category_id');
        $product->where('product_status' , 'active');
        $product->where('product_details_status' , 'active');
        if(strlen($keyword) > 0){
            $product->where(
            function ($query) use ($keyword) {
                $query->where(DB::raw('lower(view_products.name)'), 'like', '%' . strtolower($keyword) . '%')
                        ->orWhere(DB::raw('lower(view_products.vendor_name)'), 'like', '%' . strtolower($keyword) . '%')
                        ->orWhere(DB::raw('lower(view_products.category)'), 'like', '%' . strtolower($keyword) . '%');
            });
        }
        if($filter['selected_filter']){
            $arr_location = [];
            $arr_categories = [];
            foreach ($filter['selected_filter'] as $item){
                if($item['type'] == 'location'){
                    array_push($arr_location, $item['id']);
                }
                if($item['type'] == 'category'){
                    array_push($arr_categories, $item['id']);
                }
                if($item['type'] == 'range'){
                    $product->where('face_price', '>=', $filter['min_price']);
                    $product->where('face_price', '<=', $filter['max_price']);
                    $product->orderBy(DB::raw('MIN(face_price)'));
                }
            }
            if ($arr_location) {
                $product->whereIn('province_id', $arr_location);
            }
            if ($arr_categories) {
                $product->whereIn('category_id', $arr_categories);
            }
        }
        
        $product->groupBy('product_id','name','slug','value_discount','category_id','province_id');
        $response['message'] = "Success";
        $response['keyword'] = $keyword;
        $response['total_record'] = $product->get()->count();
        $response['total_page'] = ceil($response['total_record'] / 50);
        $response['current_page'] = isset($request->page) ? $request->page : 1;
        $response['product'] = ProductShortResource::collection($product->paginate(50));
        return $this->successResponse($response);
    }


    /**
     * @OA\Post(
     * path="/api/filter/bundling",
     * summary="Filter",
     * description="result filter",
     * operationId="result filter",
     * tags={"Filter"},
     *security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="province_id",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="min_price",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="max_price",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="category_id",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="review",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="shipment_service_id",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="brand_id",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
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

    public function get_bundling(Request $request){
        $filter = $request->filter;
        $keyword = isset($filter['keyword']) ? $filter['keyword'] : '' ;
        $product = ViewProduct::select('product_id','name','slug','value_discount','province_id', 'category_id');
        $product->where('product_status' , 'active');
        $product->where('category_id' ,81);
        $product->where('product_details_status' , 'active');
        if(strlen($keyword) > 0){
            $product->where(
            function ($query) use ($keyword) {
                $query->where(DB::raw('lower(view_products.name)'), 'like', '%' . strtolower($keyword) . '%')
                        ->orWhere(DB::raw('lower(view_products.vendor_name)'), 'like', '%' . strtolower($keyword) . '%')
                        ->orWhere(DB::raw('lower(view_products.category)'), 'like', '%' . strtolower($keyword) . '%');
            });
        }
        if($filter['selected_filter']){
            $arr_location = [];
            $arr_categories = [];
            foreach ($filter['selected_filter'] as $item){
                if($item['type'] == 'location'){
                    array_push($arr_location, $item['id']);
                }
                if($item['type'] == 'category'){
                    array_push($arr_categories, $item['id']);
                }
                if($item['type'] == 'range'){
                    $product->where('face_price', '>=', $filter['min_price']);
                    $product->where('face_price', '<=', $filter['max_price']);
                }
            }
            if ($arr_location) {
                $product->whereIn('province_id', $arr_location);
            }
            if ($arr_categories) {
                $product->whereIn('category_id', $arr_categories);
            }
        }
   

        $product->groupBy('product_id','name','slug','value_discount','category_id','province_id');
        $response['message'] = "Success";
        $response['keyword'] = $keyword;
        $response['total_record'] = $product->get()->count();
        $response['total_page'] = ceil($response['total_record'] / 50);
        $response['current_page'] = isset($request->page) ? $request->page : 1;
        $response['product'] = ProductShortResource::collection($product->paginate(50));
        return $this->successResponse($response);
    }


    /**
     * @OA\Post(
     * path="/api/filter/category",
     * summary="Filter",
     * description="result filter",
     * operationId="result filter",
     * tags={"Filter"},
     *security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="province_id",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="min_price",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="max_price",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="category_id",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="review",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="shipment_service_id",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="brand_id",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
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

    public function get_category(Request $request){
        $filter = $request->filter;
        $categories = Category::where('parent_id' , $filter['category_id'])->pluck('id');
        $keyword = isset($filter['keyword']) ? $filter['keyword'] : '' ;
        $product = ViewProduct::select('product_id','name','slug','value_discount','province_id', 'category_id');
        $product->where('product_status' , 'active');
        if($categories){
            $product->whereIn('category_id' ,  $categories);
        }
        $product->where('product_details_status' , 'active');
        if(strlen($keyword) > 0){
            $product->where(
            function ($query) use ($keyword) {
                $query->where(DB::raw('lower(view_products.name)'), 'like', '%' . strtolower($keyword) . '%')
                        ->orWhere(DB::raw('lower(view_products.vendor_name)'), 'like', '%' . strtolower($keyword) . '%')
                        ->orWhere(DB::raw('lower(view_products.category)'), 'like', '%' . strtolower($keyword) . '%');
            });
        }
        if($filter['selected_filter']){
            $arr_location = [];
            $arr_categories = [];
            foreach ($filter['selected_filter'] as $item){
                if($item['type'] == 'location'){
                    array_push($arr_location, $item['id']);
                }
                if($item['type'] == 'category'){
                    array_push($arr_categories, $item['id']);
                }
                if($item['type'] == 'range'){
                    $product->where('face_price', '>=', $filter['min_price']);
                    $product->where('face_price', '<=', $filter['max_price']);
                }
            }
            if ($arr_location) {
                $product->whereIn('province_id', $arr_location);
            }
            if ($arr_categories) {
                $product->whereIn('category_id', $arr_categories);
            }
        }
   

        $product->groupBy('product_id','name','slug','value_discount','category_id','province_id');
        $response['message'] = "Success";
        $response['keyword'] = $keyword;
        $response['total_record'] = $product->get()->count();
        $response['total_page'] = ceil($response['total_record'] / 50);
        $response['current_page'] = isset($request->page) ? $request->page : 1;
        $response['product'] = ProductShortResource::collection($product->paginate(50));
        return $this->successResponse($response);
    }


    public function master_status_order(){
        $master = MasterStatusOrder::select('status_code as code','description_vendor as description')->where('status' ,'active')->get();
        return $this->successResponse($master);
    }


}
