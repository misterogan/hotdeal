<?php

namespace App\Http\Controllers\Api;

use App\Cart;
use App\Http\Controllers\Controller;
use App\Http\Resources\CouponResource;
use App\LogVoucherUsage;
use App\Order;
use App\OrderVouchers;
use App\PromotionDiscount;
use App\PromotionDiscountProduct;
use App\PromotionVoucher;
use App\PromotionVoucherProduct;
use Carbon\Carbon;
use Google\Service\CustomSearchAPI\Promotion;
use Illuminate\Http\Request;

class CouponController extends Api
{
    /**
     * @OA\Post(
     * path="/api/voucher/vendor/get",
     * summary="Voucher",
     * description="result store Coupon",
     * operationId="result store Coupon",
     * tags={"Voucher"},
     *security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="vendor_id",
     *          required=true,
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
    public function get_coupon_store(Request $request){
        $user = $this->user();
        $date_now = date('Y-m-d H:i:s');
        $vendor_id = $request->vendor_id;
        $coupons = PromotionVoucher::where('vendor_id',$vendor_id)
            ->where('status','active')
            ->where('start_date','<=',$date_now)
            ->where('end_date','>=',$date_now)
            ->where(function ($query) use ($user) {
                $query->where('apply_to_all_user',true)
                    ->orWhere('user_id',$user->id);
            })
            ->orderBy('id','ASC')
            ->get();
        $response['coupons'] = CouponResource::collection($coupons);
        $response['total_payment'] = $request->total_payment;
        return $this->successResponse($response);
    }

    /**
     * @OA\Post(
     * path="/api/voucher/vendor/claim",
     * summary="Voucher",
     * description="result claim Coupon",
     * operationId="result claim Coupon",
     * tags={"Voucher"},
     *security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="vendor_id",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="voucher_code",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="voucher_id",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="total_payment",
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
    public function insert_coupon_store(Request $request){
        $vendor_id = $request->vendor_id;
        $total_payment = $request->total_payment;
        $now = date('Y-m-d H:i:s');
        $user = $this->user();
        if($request->has('voucher_code')){
            $coupons = PromotionVoucher::where('vendor_id',$vendor_id)->where('voucher_code',$request->voucher_code)->first();
        }else if($request->has('voucher_id')){
            $coupons = PromotionVoucher::where('id',$request->voucher_id)->first();
        }
        
        if(!$coupons){
            return $this->errorResponse(static::COUPON_NOT_FOUND,static::COUPON_NOT_FOUND_CODE);
        }
        else if( $now < $coupons->start_date){
            return $this->errorResponse(static::COUPON_NOT_STARTED,static::COUPON_NOT_STARTED_CODE);
        }
        else if( $now > $coupons->end_date){
            return $this->errorResponse(static::COUPON_EXPIRED,static::COUPON_NOT_FOUND_CODE);
        }
        else if($coupons->apply_to_all_user == false && $coupons->user_id != $user->id ){
            return $this->errorResponse(static::COUPON_NOT_AVAILABLE,static::COUPON_NOT_AVAILABLE_CODE);
        }
        else if($total_payment < $coupons->minimum_payment ){
            return $this->errorResponse(static::COUPON_NOT_MATCH,static::COUPON_NOT_MATCH_CODE);
        }
        $discount_amount = 0;
        
        if($coupons->discount_type == 'nominal'){
            $discount_amount = $coupons->value_discount;
        }else{
            $discount_amount = $total_payment * floatval($coupons->value_discount) / 100;
            if($discount_amount > $coupons->maximum_promo){
                $discount_amount = $coupons->maximum_promo;
            }
        }
        $response = [
            'voucher_name' => $coupons->voucher_name,
            'vendor_id' => $coupons->vendor_id,
            'voucher_code' => $coupons->voucher_code,
            'voucher_description' => $coupons->voucher_description,
            'voucher_id' => $request->voucher_id,
            'status' => true,
            'voucher_value' => (int)($discount_amount),
        ];
        return $this->successResponse($response);
    }

    /**
     * @OA\Get(
     * path="/api/voucher/hotdeal/get",
     * summary="Voucher",
     * description="result hotdeal Coupon",
     * operationId="result hotdeal Coupon",
     * tags={"Voucher"},
     *security={ {"sanctum": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(
     *        )
     *     )
     * )
     */

    public function get_hotdeal_voucher(Request $request){
        $user = $this->user();
        $date_now = date('Y-m-d H:i:s');
        $coupons = PromotionVoucher::where('voucher_type','hotdeal')
            ->where('status','active')
            ->where('start_date','<=',$date_now)
            ->where('end_date','>=',$date_now)
            ->where(function ($query) use ($user) {
                $query->where('apply_to_all_user',true)
                    ->orWhere('user_id',$user->id);
            })
        ->orderBy('id','ASC')
        ->get();
        $response['coupons'] = CouponResource::collection($coupons);
        return $this->successResponse($response);
    }

    /**
     * @OA\Post(
     * path="/api/voucher/hotdeal/claim",
     * summary="Voucher",
     * description="result claim Coupon",
     * operationId="result claim Coupon",
     * tags={"Voucher"},
     *security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="voucher_code",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="voucher_id",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="total_payment",
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
    public function claim_hotdeal_coupon(Request $request){
        $total_payment = $request->total_payment;
        $now = date('Y-m-d H:i:s');
        $user = $this->user();
        // $products = Cart::with('products')->where('user_id', $user->id)
        //             ->where('status', 'active')
        //             ->where('in_checkout', true)
        //             ->get();

        $products = Cart::select('products.name','products.weight' ,'products.dimension' ,'products.id','cart.product_details_id','products.vendor_id' ,'cart.quantity' ,'view_products.*')
            ->with('products')
            ->join('view_products' ,'view_products.product_detail_id' , 'cart.product_details_id')
            ->join('products' ,'view_products.product_id' , 'products.id' )
            ->where('user_id',$user->id)
            ->where('cart.status', 'active')
            ->where('products.status', 'active')
            ->where('in_checkout', true)
            ->get();
        $total_payment_product = 0;
        $all_id_product = [];
        if($products){
            foreach($products as $key=>$val){
                $total_payment_product += ($val->face_price * $val->quantity);
                $all_id_product[$val->product_id] = ($val->face_price * $val->quantity);
            }
        }

        if($request->has('voucher_code')){
            $coupons = PromotionVoucher::where('voucher_code',$request->voucher_code)->with('products')->first();
            
            $check_voucher_code = Order::select('detail_voucher', 'order_payments.updated_at', 'order_payments.status', 'order_payments.id')
                                    ->leftJoin('order_vouchers', 'orders.id', 'order_vouchers.order_id')
                                    ->leftJoin('order_payments', 'orders.id', 'order_payments.order_id')
                                    ->where('user_id', $user->id)
                                    ->where('order_payments.status', 'SUCCESS')
                                    ->whereBetween('order_payments.updated_at', [Carbon::now()->subHours(48), Carbon::now()])
                                    ->get();
            $voucher_checked = false;
            $time_check_voucher = false;
            foreach ($check_voucher_code as  $value) {
                $val = json_decode($value->detail_voucher);
                if($val == null){
                    continue;
                }
                if($val->voucher_code == $request->voucher_code){
                    $time_check_voucher = true;
                    $voucher_checked = true;
                    break;
                }
            }

            $is_multiple = $coupons->is_multiple;
            if($coupons->is_code && $time_check_voucher){
                return $this->errorResponse(static::COUPON_IS_EMPTY,static::COUPON_NOT_FOUND_CODE);
            } else if(!$is_multiple && $voucher_checked){
                return $this->errorResponse(static::COUPON_HAS_USED,static::COUPON_NOT_FOUND_CODE);
            } else if($coupons->is_code && $coupons->total < 1){
                return $this->errorResponse(static::COUPON_NOT_AVAILABLE, static::COUPON_NOT_FOUND_CODE);
            }
        }else if($request->has('voucher_id')){
            $coupons = PromotionVoucher::where('id',$request->voucher_id)->with('products')->first();
        }

        

        $exclude_discount = 0;
        $total_payment = $total_payment - ($total_payment - $total_payment_product);
        
        if($coupons->apply_to_all_product == false){
            $is_product = false;
            foreach($coupons->products as $coupon){
               if(array_key_exists($coupon->product_id , $all_id_product)){
                    $is_product = true;
                    $exclude_discount += $all_id_product[$coupon->product_id];
               }
            }
            if(!$is_product){
                return $this->errorResponse('Voucher hanya untuk Jenis produk tertentu.', static::COUPON_NOT_MATCH_CODE);
            }

            if($coupons->amount_product_only){
                $total_payment = $total_payment_product - ($total_payment_product - $exclude_discount);
            }
        }

        if(!$coupons){
            return $this->errorResponse(static::COUPON_NOT_FOUND,static::COUPON_NOT_FOUND_CODE);
        }
        else if( $now < $coupons->start_date){
            return $this->errorResponse(static::COUPON_NOT_STARTED,static::COUPON_NOT_STARTED_CODE);
        }
        else if( $now > $coupons->end_date){
            return $this->errorResponse(static::COUPON_EXPIRED,static::COUPON_NOT_FOUND_CODE);
        }
        else if($coupons->apply_to_all_user == false && $coupons->user_id != $user->id ){
            return $this->errorResponse(static::COUPON_NOT_AVAILABLE,static::COUPON_NOT_AVAILABLE_CODE);
        }
        else if($total_payment < $coupons->minimum_payment ){
            return $this->errorResponse(static::COUPON_NOT_MATCH,static::COUPON_NOT_MATCH_CODE);
        }

        if($coupons->discount_type == 'nominal'){
            $calculation_price = $total_payment - floatval($coupons->value_discount);
            $discount_amount = $coupons->value_discount;
            if($calculation_price < 1){
                $calculation_price = 0;
                $discount_amount = $total_payment;
            }
        }else{
            $discount_amount = $total_payment * floatval($coupons->value_discount)  / 100;
            
            if($discount_amount > intval($coupons->maximum_promo) && $coupons->maximum_promo != 0){
                $discount_amount = $coupons->maximum_promo;
            }
            $calculation_price = $total_payment - $discount_amount;
        }

        // Log Voucher
        if($coupons->is_code){
            LogVoucherUsage::create([
                'promotion_voucher_id' => $coupons->id,
                'user_id' => $user->id,
                'code' => $coupons->voucher_code,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
       //echo $exclude_discount;
        $response['discount_amount'] = $discount_amount;
        $response['calculation_price'] = $calculation_price;

        return $this->successResponse($response);
    }

    /**
     * @OA\Post(
     * path="/api/voucher/remove",
     * summary="Voucher",
     * description="remove Coupon",
     * operationId="remove Coupon",
     * tags={"Voucher"},
     *security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="voucher_id",
     *          required=true,
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
    public function delete_coupon_store(Request $request){
        $voucher_id = $request->voucher_id;
        $coupons = PromotionVoucher::where('id',$request->voucher_code)->first();
    }


}
