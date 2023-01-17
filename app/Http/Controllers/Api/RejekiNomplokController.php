<?php

namespace App\Http\Controllers\Api;

use App\Product;
use App\Helpers\RejekiNomplok;
use App\Http\Resources\CouponCustomerResource;
use App\Http\Resources\ProductShortResource;
use App\Http\Resources\RejekiNomplokWeekResources;
use App\PromotionVoucher;
use App\RejekiNomplokBanner;
use App\RejekiNomplokCoupon;
use App\RejekiNomplokProduct;
use App\RejekiNomplokWeek;
use App\RejekiNomplokWinners;
use App\RunningText;
use App\ViewProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RejekiNomplokController extends Api
{
    /**
     * @OA\Get (
     * path="/api/rejeki-nomplok/get",
     * summary="Rejeki Nomplok - General",
     * description="get Coupons list",
     * operationId="get Coupons list",
     * tags={"Rejeki Nomplok"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(

     *        )
     *     )
     * )
     */
    public function get(Request $request) {
        // $products = ViewProduct::select('products.id as product_id','products.name','products.slug')
        //             ->join('products', 'view_products.product_id', 'products.id')
        //             ->join('rejeki_nomplok_products', 'products.id', 'rejeki_nomplok_products.product_id')
        //             ->where('products.status', 'active')
        //             ->where('rejeki_nomplok_products.status', 'active') 
        //             ->orderby('rejeki_nomplok_products.updated_at', 'DESC')
        //             ->groupBy('products.id', 'rejeki_nomplok_products.updated_at', 'view_products.face_price');
        // dd($request->all());
        $products = Product::select('products.id as product_id','products.name','products.slug', 'products.category_id')
                ->with('category')
                ->leftJoin('view_products', 'products.id', 'view_products.product_id')
                ->leftJoin('rejeki_nomplok_products', 'products.id', 'rejeki_nomplok_products.product_id')
                ->where('rejeki_nomplok_products.status', 'active') 
                ->where('products.status' , 'active')
                ->where('product_status', 'active')
                ->where('product_details_status', 'active')
                ->groupBy('products.id','products.name','products.slug', 'products.category_id', 'rejeki_nomplok_products.updated_at');
        
        if (isset($request->filter)) {
            // dd("here");
            $filter = $request->filter;
            if($filter == 'new'){
                $products->orderByDesc('rejeki_nomplok_products.updated_at');
            } else if($filter == 'low'){
                $products->orderBy(DB::raw('min(view_products.face_price)'));
            } else if($filter == 'high'){
                $products->orderByDesc(DB::raw('min(view_products.face_price)'));
            }
        }

        $data = [
            'list' => ProductShortResource::collection($products->paginate(10))
        ];
        return $this->successResponse($data);
    }

    public function filter(Request $request) {
        $products = Product::select('products.id as product_id','products.name','products.slug', 'products.category_id')
                ->with('category')
                ->leftJoin('view_products', 'products.id', 'view_products.product_id')
                ->leftJoin('rejeki_nomplok_products', 'products.id', 'rejeki_nomplok_products.product_id')
                ->where('rejeki_nomplok_products.status', 'active') 
                ->where('products.status' , 'active')
                ->where('product_status', 'active')
                ->where('product_details_status', 'active')
                ->groupBy('products.id','products.name', 'rejeki_nomplok_products.updated_at');

        if($request->filter == 'new'){
            $products->orderByDesc('rejeki_nomplok_products.updated_at');
        } else if($request->filter == 'low'){
            $products->orderBy(DB::raw('min(view_products.face_price)'));
        } else if($request->filter == 'high'){
            $products->orderByDesc(DB::raw('min(view_products.face_price)'));
        } else if($request->filter == 'rating'){
            
        }

        $data = [
            'list' => ProductShortResource::collection($products->get())
        ];
        return $this->successResponse($data);
    }

    /**
     * @OA\Get (
     * path="/api/rejeki-nomplok/get/mycoupons",
     * summary="Rejeki Nomplok - Kupon Saya",
     * description="get Coupons list",
     * operationId="get Coupons list",
     * tags={"Rejeki Nomplok"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(

     *        )
     *     )
     * )
     */
    public function get_mycoupons(Request $request) {
        $user = $this->user();
        if(!$user){
            return $this->successResponse([]);
        }
        $query = RejekiNomplokCoupon::where('user_id' , $user->id)->with('order')->with('product')->with('week');
        if($request->filter != 'all'){
            if($request->filter == 'winner'){
                $query->where('is_winner' , true);
            }else{
                $query->where('status' , $request->filter);
                $query->where('is_winner' ,'!=',true);
            }
        }
        $query->orderBy('id' , 'DESC');
        $coupons = $query->paginate(10);
        if($coupons){
            $data['coupon'] = $coupons ? CouponCustomerResource::collection($coupons) : [];
            $data['current_page'] = $coupons->currentPage();
            $data['total'] = $coupons->lastPage();
            return $this->successResponse($data);
        }else{
            return $this->successResponse([]);
        }
        //return $this->successResponse($coupons);
    }


    /**
     * @OA\Get (
     * path="/api/rejeki-nomplok/active",
     * summary="Rejeki Nomplok - Active Period",
     * description="get Period ",
     * operationId="get Period",
     * tags={"Rejeki Nomplok"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(

     *        )
     *     )
     * )
     */
    public function active_period() {

        $active = RejekiNomplokWeek::where('start_date' , '>' , date('Y-m-d'))->first();
        if($active){
            $data = new RejekiNomplokWeekResources($active);
            return $this->successResponse($data);
        }
        return $this->errorResponse('Error rejeki nomplok' , 402);

    }



    /**
     * @OA\Post (
     * path="/api/ejeki-nomplok/get/winners",
     * summary="Rejeki Nomplok - Winners",
     * description="get Winners list",
     * operationId="get Winners list",
     * tags={"Rejeki Nomplok"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(

     *        )
     *     )
     * )
     */
    public function get_winners(Request $request) {
        $week = RejekiNomplokWeek::where('week' , $request->week)->first();
        if(!$week){
            return $this->successResponse([]);
        }
        $winners = RejekiNomplokCoupon::where('rejeki_nomplok_week_id' , $week->id)->where('is_winner' , true)->with('user')->with('product');
        $data['week'] = new RejekiNomplokWeekResources($week);
        $data['current_page'] = isset($request->page) ? $request->page : 1;
        $data['total_record'] = $winners->get()->count();
        $data['total_page'] = ceil($data['total_record'] / 2);
        $data['winner'] = $winners->paginate(2);
        return $this->successResponse($data);
    }

    public function periodList(Request $request) {
        $per_page = isset($request->per_page) ? $request->per_page : 6;
        $today = Carbon::now()->subDays(7);
        $week = RejekiNomplokWeek::leftJoin('rejeki_nomplok_coupons', 'rejeki_nomplok_weeks.id', 'rejeki_nomplok_coupons.rejeki_nomplok_week_id')
                                    ->select('rejeki_nomplok_weeks.week as week', 'rejeki_nomplok_weeks.start_date', 'rejeki_nomplok_weeks.end_date', 'rejeki_nomplok_weeks.ihsg', 'rejeki_nomplok_weeks.id as id', DB::raw('SUM(value_transaction) as total_point'))
                                    ->groupBy('rejeki_nomplok_weeks.id')
                                    ->where('end_date','<',$today);
        if($request->filter == 'high'){
            $week->orderByRaw('SUM(value_transaction) DESC');
        } else if($request->filter == 'low'){
            $week->orderByRaw('SUM(value_transaction) ASC');
        } else {
            $week->orderByDesc('id');
        }
        if(!$week){
            return $this->successResponse([]);
        }
        $data['week'] = RejekiNomplokWeekResources::collection($week->paginate($per_page));
        return $this->successResponse($data);
    }

    public function winner_text(){
        $week = RejekiNomplokWeek::where('status', 'active')->latest()->first();
        $winner = RejekiNomplokCoupon::leftjoin('users', 'rejeki_nomplok_coupons.user_id', '=', 'users.id')
                ->select('name')
                ->where('rejeki_nomplok_week_id', $week->id)
                ->where('is_winner', true)
                ->get();

        $name = "";
        foreach($winner as $n => $win){
            if($n % 2 == 0){
                $name .= $win["name"] . ', ';
            }
            else{
                $name .= $win["name"] . ' ';
            }
        }
        return $this->successResponse($name);
    }

    public function banner(Request $request) {
        $banner = RejekiNomplokBanner::select('banner', 'banner_mobile')->first();
        return $this->successResponse($banner);
    }

    public function running_text(){
        $text = RunningText::where('id', 1)->where('status', 'active')->first();
        return $this->successResponse($text);
    }

    public function winner(){
        $data = RunningText::where('id', 1)->where('status', 'active')->first();
        if($data){
            $text = $data->text;
        } else {
            $text = null;
        }
        return $text;
    }

    public function current_week(){
        $now = Carbon::now();
        $id = RejekiNomplokWeek::where('status', 'active')
                    ->orderByDesc('id')->pluck('id')->first();
        $week = RejekiNomplokWeek::where('status', 'active')->whereNotNull('ihsg')->orderByDesc('id')->first();
        
        $total = RejekiNomplokCoupon::select(DB::raw('sum(value_transaction) as total_bonus'))->where('rejeki_nomplok_week_id' , $week->id)->first();
        $winner = $this->winner();
        $hotpoint = RejekiNomplokCoupon::select(DB::raw('sum(point_sent) as point'))->where('rejeki_nomplok_week_id' , $week->id)->where('is_winner', true)->first();
        
        if($week){
            $data = [
                'week' => $week->week,
                'ihsg' => substr($week->ihsg, 0, 3),
                'start_date' => Carbon::parse($week->start_date)->format('d'),
                'end_date' => Carbon::parse($week->end_date)->format('d'),
                'start_month' => Carbon::parse($week->start_date)->format('M'),
                'end_month' => Carbon::parse($week->end_date)->format('M'),
                'end_year' => Carbon::parse($week->end_date)->format('Y'),
                'last_ihsg' => substr($week->ihsg, -1),
                'total_point' => $total ?  (int)($total->total_bonus) : 0,
                'winner' => $winner,
                'total_hotpoint' => $hotpoint->point
            ];
            return $this->successResponse($data);
        }

        return $this->errorResponse('Error rejeki nomplok' , 402);
    }
}
