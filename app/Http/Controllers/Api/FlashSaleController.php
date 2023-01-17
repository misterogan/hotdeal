<?php

namespace App\Http\Controllers\Api;

use App\FlashSale;
use App\FlashSaleDetail;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryFilterResource;
use App\Http\Resources\CustomerProductsResource;
use App\Http\Resources\FlashSaleResource;
use App\Http\Resources\ProductLocationResource;
use App\Http\Resources\ProductShortResource;
use App\Product;
use App\Vendor;
use App\ViewProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FlashSaleController extends Api
{
    /**
     * @OA\Get(
     * path="/api/flash-sale",
     * summary="flash-sale",
     * description="get flash-sale",
     * operationId="FlashSale",
     * tags={"Flash-Sale"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(
     *        )
     *     )
     * ) 
     */
    public function flashsale(Request $request)
    {
        $flash_data = FlashSale::where('status' , 'active')->where('start_date', '<=', Carbon::now())->where('end_date', '>', Carbon::now())->first();
        $flash_sale  = ViewProduct::select('view_products.product_id','view_products.name','view_products.slug','view_products.value_discount')
        ->join('flash_sale_details' ,'view_products.product_id','flash_sale_details.product_id')
        ->join('flash_sales' , 'flash_sale_details.flash_sale_id' ,'flash_sales.id' )
        ->where('flash_sales.status','active')
        ->where('flash_sales.start_date', '<=', Carbon::now())
        ->where('flash_sales.end_date', '>', Carbon::now())
        ->groupBy('view_products.product_id','view_products.name','view_products.slug','view_products.value_discount','category_id')
        ->limit(15)
        ->get();

        $tmp  = ViewProduct::select('view_products.product_id','view_products.name','view_products.slug','view_products.value_discount', 'view_products.category_id')
            ->join('flash_sale_details' ,'view_products.product_id','flash_sale_details.product_id')
            ->join('flash_sales' , 'flash_sale_details.flash_sale_id' ,'flash_sales.id' )
            ->where('flash_sales.status','active')
            ->where('flash_sales.start_date', '<=', Carbon::now())
            ->where('flash_sales.end_date', '>', Carbon::now())
            ->groupBy('view_products.product_id','view_products.name','view_products.slug','view_products.value_discount','category_id')
            ->limit(15);

        $product_ids  = ViewProduct::select('view_products.product_id','view_products.name','view_products.slug','view_products.value_discount')
            ->join('flash_sale_details' ,'view_products.product_id','flash_sale_details.product_id')
            ->join('flash_sales' , 'flash_sale_details.flash_sale_id' ,'flash_sales.id' )
            ->where('flash_sales.status','active')
            ->where('flash_sales.start_date', '<=', Carbon::now())
            ->where('flash_sales.end_date', '>', Carbon::now())
            ->groupBy('view_products.product_id','view_products.name','view_products.slug','view_products.value_discount','category_id')
            ->limit(15)
            ->pluck('product_id');

        $product_location = Vendor::select('provinces.id as province_id','provinces.name as province_name',DB::raw('count(*) as total'))
            ->leftJoin('provinces',function($join)  {
                $join->on('vendors.province_id', '=', 'provinces.id');
            })->leftJoin('products',function($join) {
                $join->on('vendors.id', '=', 'products.vendor_id');
            }
            )->whereIn('products.id', $product_ids)->groupBy('provinces.id','provinces.name')
            ->get();

        if($flash_data && $flash_sale){
            $data = [
                'flash_data' => [

                    "name" =>  $flash_data->name,
                    "banner_type" => $flash_data->banner_type,
                    "status" => $flash_data->status,
                    "start_date"=>  $flash_data->start_date,
                    "end_date"=>  $flash_data->end_date,
                    "year" => (int)(date('Y' , strtotime($flash_data->end_date))),
                    "month" => (int)(date('m' , strtotime($flash_data->end_date))),
                    "day" => (int)(date('d' , strtotime($flash_data->end_date))),
                    "hours" => (int)(date('H' , strtotime($flash_data->end_date))),
                    "minute" => (int)(date('i' , strtotime($flash_data->end_date))),
                    "second" => (int)(date('s' , strtotime($flash_data->end_date))),
                    "banner"=> $flash_data->banner,
                    "banner_mobile"=> $flash_data->banner_mobile,
                    "slug"=> $flash_data->slug,
                ],
                'product' => ProductShortResource::collection($flash_sale),
                'is_flashSale' => true,
                'location' => ProductLocationResource::collection($product_location),
                'category' => CategoryFilterResource::collection($tmp->distinct('category_id')->get())
            ];
            return $this->successResponse($data);
        } else {
            $data = [
                'flash_data' => [],
                'product' => [],
                'is_flashSale' => false,
            ];
            return $this->successResponse($data);
        }

    }


    public function flashevent(Request $request)
    {
        $flash_data = FlashSale::where('status' , 'active')->where('start_date', '<=', Carbon::now())->where('end_date', '>', Carbon::now())->first();
        $flash_sale  = ViewProduct::select('view_products.product_id','view_products.name','view_products.slug','view_products.value_discount')
        ->join('flash_sale_details' ,'view_products.product_id','flash_sale_details.product_id')
        ->join('flash_sales' , 'flash_sale_details.flash_sale_id' ,'flash_sales.id' )
        ->where('flash_sales.status','active')
        ->where('flash_sales.start_date', '<=', Carbon::now())
        ->where('flash_sales.end_date', '>', Carbon::now())
        ->groupBy('view_products.product_id','view_products.name','view_products.slug','view_products.value_discount','category_id')
        ->limit(15)
        ->get();

        $tmp  = ViewProduct::select('view_products.product_id','view_products.name','view_products.slug','view_products.value_discount', 'view_products.category_id')
            ->join('flash_sale_details' ,'view_products.product_id','flash_sale_details.product_id')
            ->join('flash_sales' , 'flash_sale_details.flash_sale_id' ,'flash_sales.id' )
            ->where('flash_sales.status','active')
            ->where('flash_sales.start_date', '<=', Carbon::now())
            ->where('flash_sales.end_date', '>', Carbon::now())
            ->groupBy('view_products.product_id','view_products.name','view_products.slug','view_products.value_discount','category_id')
            ->limit(15);

        $product_ids  = ViewProduct::select('view_products.product_id','view_products.name','view_products.slug','view_products.value_discount')
            ->join('flash_sale_details' ,'view_products.product_id','flash_sale_details.product_id')
            ->join('flash_sales' , 'flash_sale_details.flash_sale_id' ,'flash_sales.id' )
            ->where('flash_sales.status','active')
            ->where('flash_sales.start_date', '<=', Carbon::now())
            ->where('flash_sales.end_date', '>', Carbon::now())
            ->groupBy('view_products.product_id','view_products.name','view_products.slug','view_products.value_discount','category_id')
            ->limit(15)
            ->pluck('product_id');

        $product_location = Vendor::select('provinces.id as province_id','provinces.name as province_name',DB::raw('count(*) as total'))
            ->leftJoin('provinces',function($join)  {
                $join->on('vendors.province_id', '=', 'provinces.id');
            })->leftJoin('products',function($join) {
                $join->on('vendors.id', '=', 'products.vendor_id');
            }
            )->whereIn('products.id', $product_ids)->groupBy('provinces.id','provinces.name')
            ->get();

        if($flash_data && $flash_sale){
            $data = [
                'flash_data' => [

                    "name" =>  $flash_data->name,
                    "banner_type" => $flash_data->banner_type,
                    "status" => $flash_data->status,
                    "start_date"=>  $flash_data->start_date,
                    "end_date"=>  $flash_data->end_date,
                    "year" => (int)(date('Y' , strtotime($flash_data->end_date))),
                    "month" => (int)(date('m' , strtotime($flash_data->end_date))),
                    "day" => (int)(date('d' , strtotime($flash_data->end_date))),
                    "hours" => (int)(date('H' , strtotime($flash_data->end_date))),
                    "minute" => (int)(date('i' , strtotime($flash_data->end_date))),
                    "second" => (int)(date('s' , strtotime($flash_data->end_date))),
                    "banner"=> $flash_data->banner,
                    "banner_mobile"=> $flash_data->banner_mobile,
                    "slug"=> $flash_data->slug,
                ],
                'product' => ProductShortResource::collection($flash_sale),
                'is_flashSale' => true,
                'location' => ProductLocationResource::collection($product_location),
                'category' => CategoryFilterResource::collection($tmp->distinct('category_id')->get())
            ];
            return $this->successResponse($data);
        } else {
            $data = [
                'flash_data' => [],
                'product' => [],
                'is_flashSale' => false,
            ];
            return $this->successResponse($data);
        }

    }

    public function flashsaleAll(Request $request)
    {
        $flash_data = FlashSale::where('status' , 'active')->where('start_date', '<=', Carbon::now())->where('end_date', '>', Carbon::now())->first();
        $flash_sale  = ViewProduct::select('view_products.product_id','view_products.name','view_products.slug','view_products.value_discount')
        ->join('flash_sale_details' ,'view_products.product_id','flash_sale_details.product_id')
        ->join('flash_sales' , 'flash_sale_details.flash_sale_id' ,'flash_sales.id' )
        ->where('flash_sales.status','active')
        ->where('flash_sales.start_date', '<=', Carbon::now())
        ->where('flash_sales.end_date', '>', Carbon::now())
        ->groupBy('view_products.product_id','view_products.name','view_products.slug','view_products.value_discount','category_id')
        //->limit(15)
        ->get();

        $tmp  = ViewProduct::select('view_products.product_id','view_products.name','view_products.slug','view_products.value_discount', 'view_products.category_id')
            ->join('flash_sale_details' ,'view_products.product_id','flash_sale_details.product_id')
            ->join('flash_sales' , 'flash_sale_details.flash_sale_id' ,'flash_sales.id' )
            ->where('flash_sales.status','active')
            ->where('flash_sales.start_date', '<=', Carbon::now())
            ->where('flash_sales.end_date', '>', Carbon::now())
            ->groupBy('view_products.product_id','view_products.name','view_products.slug','view_products.value_discount','category_id');
           // ->limit(15);

        $product_ids  = ViewProduct::select('view_products.product_id','view_products.name','view_products.slug','view_products.value_discount')
            ->join('flash_sale_details' ,'view_products.product_id','flash_sale_details.product_id')
            ->join('flash_sales' , 'flash_sale_details.flash_sale_id' ,'flash_sales.id' )
            ->where('flash_sales.status','active')
            ->where('flash_sales.start_date', '<=', Carbon::now())
            ->where('flash_sales.end_date', '>', Carbon::now())
            ->groupBy('view_products.product_id','view_products.name','view_products.slug','view_products.value_discount','category_id')
            //->limit(15)
            ->pluck('product_id');

        $product_location = Vendor::select('provinces.id as province_id','provinces.name as province_name',DB::raw('count(*) as total'))
            ->leftJoin('provinces',function($join)  {
                $join->on('vendors.province_id', '=', 'provinces.id');
            })->leftJoin('products',function($join) {
                $join->on('vendors.id', '=', 'products.vendor_id');
            }
            )->whereIn('products.id', $product_ids)->groupBy('provinces.id','provinces.name')
            ->get();

        if($flash_data && $flash_sale){
            $data = [
                'flash_data' => [

                    "name" =>  $flash_data->name,
                    "banner_type" => $flash_data->banner_type,
                    "status" => $flash_data->status,
                    "start_date"=>  $flash_data->start_date,
                    "end_date"=>  $flash_data->end_date,
                    "year" => (int)(date('Y' , strtotime($flash_data->end_date))),
                    "month" => (int)(date('m' , strtotime($flash_data->end_date))),
                    "day" => (int)(date('d' , strtotime($flash_data->end_date))),
                    "hours" => (int)(date('H' , strtotime($flash_data->end_date))),
                    "minute" => (int)(date('i' , strtotime($flash_data->end_date))),
                    "second" => (int)(date('s' , strtotime($flash_data->end_date))),
                    "banner"=> $flash_data->banner,
                    "banner_mobile"=> $flash_data->banner_mobile,
                    "slug"=> $flash_data->slug,
                ],
                'product' => ProductShortResource::collection($flash_sale),
                'is_flashSale' => true,
                'location' => ProductLocationResource::collection($product_location),
                'category' => CategoryFilterResource::collection($tmp->distinct('category_id')->get())
            ];
            return $this->successResponse($data);
        } else {
            $data = [
                'flash_data' => [],
                'product' => [],
                'is_flashSale' => false,
            ];
            return $this->successResponse($data);
        }

    }

    public function flashsale_filter(Request $request)
    {
        $filter = $request->filter;
        $flash_data = FlashSale::where('status' , 'active')->where('start_date', '<=', Carbon::now())->where('end_date', '>', Carbon::now())->first();
        $flash_sale  = ViewProduct::select('view_products.product_id','view_products.name','view_products.slug','view_products.value_discount')
        ->join('flash_sale_details' ,'view_products.product_id','flash_sale_details.product_id')
        ->join('flash_sales' , 'flash_sale_details.flash_sale_id' ,'flash_sales.id' )
        ->where('flash_sales.status','active')
        ->where('flash_sales.start_date', '<=', Carbon::now())
        ->where('flash_sales.end_date', '>', Carbon::now())
        ->groupBy('view_products.product_id','view_products.name','view_products.slug','view_products.value_discount','category_id'); 

        if($filter['selected_filter']){
            $arr_categories = [];
            $arr_location = [];
            foreach ($filter['selected_filter'] as $item) {
                if($item['type'] == 'category'){
                    array_push($arr_categories, $item['id']);
                }
                if($item['type'] == 'location'){
                    array_push($arr_location, $item['id']);
                }
                if($item['type'] == 'range'){
                    $flash_sale->whereBetween('face_price', [$item['min_price'], $item['max_price']]);
                }
            }
            if ($arr_categories) {
                $flash_sale->whereIn('category_id', $arr_categories);
            }
            if ($arr_location) {
                $flash_sale->whereIn('province_id', $arr_location);
            }
        }
        
        $flash_sale = $flash_sale->get();

        if($flash_data && $flash_sale){
            $data = [
                'flash_data' => [
                    "name" =>  $flash_data->name,
                    "banner_type" => $flash_data->banner_type,
                    "status" => $flash_data->status,
                    "start_date"=>  $flash_data->start_date,
                    "end_date"=>  $flash_data->end_date,
                    "year" => (int)(date('Y' , strtotime($flash_data->end_date))),
                    "month" => (int)(date('m' , strtotime($flash_data->end_date))),
                    "day" => (int)(date('d' , strtotime($flash_data->end_date))),
                    "hours" => (int)(date('H' , strtotime($flash_data->end_date))),
                    "minute" => (int)(date('i' , strtotime($flash_data->end_date))),
                    "second" => (int)(date('s' , strtotime($flash_data->end_date))),
                    "banner"=> $flash_data->banner,
                    "slug"=> $flash_data->slug,
                ],
                'product' => ProductShortResource::collection($flash_sale),
                'is_flashSale' => true
            ];
            return $this->successResponse($data);
        } else {
            $data = [
                'flash_data' => [],
                'product' => [],
                'is_flashSale' => false,
            ];
            return $this->successResponse($data);
        }

    }

}

