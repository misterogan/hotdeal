<?php

namespace App\Http\Controllers\Api\merchant;

use App\Cart;
use App\Helpers\Orders;
use App\Http\Controllers\Api\Api;
use App\Http\Resources\OrderResource;
use App\Order;
use App\OrderDetail;
use App\OrderDetailProduct;
use App\Product;
use App\User;
use App\Vendor;
use App\Visitor;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
use Yajra\DataTables\DataTables;

class GeneralController extends Api
{
    /**
     * @OA\Get(
     * path="/api/seller/general",
     * summary="GeneralInformation",
     * description="Vendor General Information",
     * operationId="GeneralInformation",
     * tags={"Vendor"},
     * security={ {"sanctum": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(
     *        )
     *     )
     * )
     */
    public function general(Request $request) {
        $user = $this->vendor();
        if ($user == null) {
            return $this->errorResponse(self::ERROR_NOT_AUTHORIZED, self::ERROR_NOT_LOGIN_CODE);
        }

        $vendor = Vendor::where('user_id', $user->id)->first();

        if (!$vendor) {
            return $this->errorResponse(self::ERROR_NOT_AUTHORIZED, self::ERROR_NOT_LOGIN_CODE);
        }

        $order_unpaid_count = Order::whereIn('status', [1])->whereHas('detail', function ($query) use ($vendor) {
            $query->where('vendor_id', $vendor->id);
        })->count();

        $order_need_processing_count = Order::where('status', 2)->whereHas('detail', function ($query) use ($vendor) {
            $query->where('vendor_id', $vendor->id);
            $query->where('status', 2);
        })->count();

        $order_need_pickup = Order::where('status', 2)->whereHas('detail', function ($query) use ($vendor) {
            $query->where('vendor_id', $vendor->id);
            $query->where('status', 3);
        })->count();

        $delivered = Order::where('status', 2)->whereHas('detail', function ($query) use ($vendor) {
            $query->where('vendor_id', $vendor->id);
            $query->where('status', 4);
        })->count();

        // $arrived = Order::where('status', 2)->whereHas('detail', function ($query) use ($vendor) {
        //     $query->where('vendor_id', $vendor->id);
        //     $query->where('status', 12);
        // })->count();
        // $arrived = OrderDetail::where('status', 5)->count();
        $arrived = Order::where('status', 2)->whereHas('detail', function ($query) use ($vendor) {
            $query->where('vendor_id', $vendor->id);
            $query->where('status', 12);
        })->count();

        $complete = Order::where('status', 2)->whereHas('detail', function ($query) use ($vendor) {
            $query->where('vendor_id', $vendor->id);
            $query->where('status', 5);
        })->count();

        // dd($arrived, $complete, $delivered);

        $refund = Order::where('status', 2)->whereHas('detail', function ($query) use ($vendor) {
            $query->where('vendor_id', $vendor->id);
            $query->whereIn('status', [6,7]);
        })->count();


        $product_out_of_stock = Product::where('vendor_id', $vendor->id)->whereHas('details', function ($query) use ($vendor) {
            $query->where('stock', 0);
        })->count();

        $total_product_visits = Visitor::where('vendor_id', $vendor->id)->count();

        $order_count = Order::whereHas('detail', function ($query) use ($vendor) {
            $query->where('vendor_id', $vendor->id);
        })->count();

        $data = [
            'order_unpaid' => $order_unpaid_count,
            'order_need_processing' => $order_need_processing_count,
            'order_need_pickup' => $order_need_pickup,
            'delivered' => $delivered,
            'arrived' => $arrived,
            'complete' => $complete,
            'refund' => $refund,
            'product_out_of_stock' => $product_out_of_stock,
            'total_visits' => 0,
            'total_product_visits' => $total_product_visits,
            'conversion_time' => '0j 0m',
            'order_count' => $order_count,
            'net_gain' => '0%',
        ];

        return $this->successResponse($data);
    }

    public function update_profile(Request $request){
        $user = $this->vendor();
        if(isset($request->phone_number)){
            $check_phone_number = User::where('phone' , $request->phone_number)->first();
            if($check_phone_number){
                if($check_phone_number->email != $user->email){
                    return $this->errorResponse(static::UNIQUE_PHONE_NUMBER,static::PHONE_HAS_VERIFY_CODE);
                }
            }
        }
        if($user){
            $account = User::where("id", $user->id)->first();
            $users =  User::where("id", $user->id)->update([
                 'name' => $request->name ? $request->name : $account->name ,
                 'phone' => $request->phone_number ? $request->phone_number : $account->phone ,
                 'email' => $request->email ? $request->email : $account->email ,
            ]);
            if($user){
                return $this->successResponse($user);
            }

        }else{
            return $this->errorResponse(static::ERROR_NOT_LOGIN,static::ERROR_NOT_LOGIN_CODE);
        }
    }

    public function sale_data(Request $request){
        $user = $this->user();
        $vendor = Vendor::where('user_id',$user->id)->first();
        $now = Carbon::now();

        $today =  $now->toDateString();
        $weekStartDate = $now->subDays(30)->startOfWeek()->format('Y-m-d');

        $date_from = isset($request->date_from) ? $request->date_from : $weekStartDate;
        $date_until = isset($request->date_to) ? $request->date_to : $today;
        $periods = CarbonPeriod::create($date_from, $date_until);
        $timestap = [];
        $soldData = [];

        foreach($periods as $period) {
            array_push($timestap,$period->format('Y-m-d'));
            
            $sold = OrderDetailProduct::select(DB::raw('sum(order_detail_products.quantity) as quantity'))
                ->join('order_details' , 'order_details.id' , 'order_detail_products.order_detail_id')
                ->join('product_details' , 'order_detail_products.product_detail_id' , 'product_details.id')
                ->join('products', 'product_details.product_id', 'products.id')
                ->where('products.vendor_id' , $vendor->id)
                ->where('order_details.status' , '5')
                ->whereDate('order_details.created_at',$period->format('Y-m-d'))
                ->sum('order_detail_products.quantity');
            array_push($soldData,$sold);
        }

        $data['sold'] = $soldData;
        $data['timestamp'] = $timestap;
        return $this->successResponse($data);
    }
}
