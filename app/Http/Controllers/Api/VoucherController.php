<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryPromotionResource;
use App\Http\Resources\PromotionVoucherResource;
use App\PromotionBanners;
use App\PromotionVoucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoucherController extends Api
{
    /**
     * @OA\post(
     * path="/api/promotion/get",
     * summary="Promotion",
     * description="get Promotion",
     * operationId="get Promotion",
     * tags={"Promotion"},
     * security={ {"bearer": {} }},
     *     @OA\Parameter(
     *          name="payment",
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
    public function get_promotion(Request $request) {
        $user = $this->user();
        $now = date('Y-m-d H:i:s');
        $payment = $request->payment;
        $vouchers = [];

        $promotion = PromotionVoucher::where('status','active')
            ->where('start_date','<=', $now)
            ->where('end_date','>=', $now)
            ->where('show_only', false)
            ->orderBy('voucher_name')->paginate($request->per_page);

        if($request->pagination['search'] != ''){
            $someFilter = strtolower($request->pagination['search']);
            $collection = $promotion->getCollection();
            $filteredCollection = $collection->filter(function($model) use ($someFilter) {
                return is_int(strpos(strtolower($model->voucher_code), $someFilter));
            });
            if($filteredCollection->count() < 1){
                $filteredCollection = $collection->filter(function($model) use ($someFilter) {
                    return is_int(strpos(strtolower($model->voucher_name), $someFilter));
                });
            };
            $promotion->setCollection($filteredCollection);
            foreach($promotion as $value){
                if( $payment >= $value->minimum_payment  &&  $value->apply_to_all_user == true || $value->user_id == $user->id){
                    array_push($vouchers,$value);
                }
            }
        } else{
            foreach ($promotion as $key => $val){
                if( $payment >= $val->minimum_payment  &&  $val->apply_to_all_user == true || $val->user_id == $user->id){
                    if(!$val->is_code){
                        array_push($vouchers,$val);
                    }
                }
            }
        }

        if($promotion){
            $data['vouchers'] = $vouchers;
            $data['current_page'] = $promotion->currentPage();
            $data['total'] = $promotion->total();

            return $this->successResponse($data);
        }
        else{
            return $this->successResponse([]);
        }
    }

    /**
     * @OA\Get (
     * path="/api/promotion/get/list",
     * summary="Promotion",
     * description="get Promotion list",
     * operationId="get Promotion list",
     * tags={"Promotion"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(

     *        )
     *     )
     * )
     */
    public function get_promotion_list(Request $request) {
        $user = $this->user();
        $now = date('Y-m-d H:i:s');

        $vouchers = PromotionVoucher::where('start_date','<=', $now)
            ->where('end_date','>=', $now)
            ->where('apply_to_all_user',true)
            ->where('status', 'active')
            ->with('category')
            ->orderByDesc('updated_at')
            ->paginate();

        $category = DB::table('promotion_vouchers')
            ->leftJoin('categories', 'promotion_vouchers.category_promotion_id', '=', 'categories.id')
            ->select('category', 'categories.id', DB::raw('count(*) as total_voucher'))
            ->whereNotNull('category_promotion_id')
            ->Where('promotion_vouchers.apply_to_all_user',true)
            ->where('promotion_vouchers.start_date','<=', $now)
            ->where('promotion_vouchers.end_date','>=', $now)
            ->where('promotion_vouchers.status', 'active')
            ->groupBy('category', 'categories.id')
            ->orderByDesc('categories.id')
            ->get();

        $banner = PromotionBanners::where('id', 1)->first();
 
        $data = [
            'vouchers' => $vouchers,
            'category' => $category,
            'banner' => $banner->banner
        ];
        return $this->successResponse($data);
    }


    /**
     * @OA\post(
     * path="/api/promotion/detail/get",
     * summary="Promotion",
     * description="get detail Promotion per id",
     * operationId="get detail Promotion per id",
     * tags={"Promotion"},
     * security={ {"bearer": {} }},
     *     @OA\Parameter(
     *          name="promotion_id",
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
    public function get_promotion_by_id(Request $request) {
        $user = $this->user();
        $now = date('Y-m-d H:i:s');
        $vouchers = PromotionVoucher::select('id','voucher_name','image')->where('id',$request->promotion_id)->first();
        $data = [
            'vouchers' => $vouchers,
        ];
        return $this->successResponse($data);
    }

    /**
     * @OA\Post (
     * path="/api/promotion/get/category/list",
     * summary="Promotion",
     * description="get Promotion category list",
     * operationId="get Promotion category list",
     * tags={"Promotion"},
     * security={ {"bearer": {} }},
     *     @OA\Parameter(
     *          name="category_id",
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
    public function get_promotion_by_category(Request $request) {
        $user = $this->user();
        $now = date('Y-m-d H:i:s');
        $vouchers = PromotionVoucher::where('user_id',$user->id)
            ->orWhere('apply_to_all_user',true)
            ->where('start_date','<=', $now)
            ->where('end_date','>=', $now)
            ->where('category_promo_id', $request->category_id)
            ->paginate();
        $data = [
            'vouchers' => $vouchers,
        ];
        return $this->successResponse($data);
    }
}
