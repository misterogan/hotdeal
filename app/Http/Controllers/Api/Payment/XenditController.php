<?php

namespace App\Http\Controllers\Api\Payment;

use App\CallbackPayment;
use App\Http\Controllers\Api\Api;
use App\Http\Controllers\Controller;
use App\MasterBank;
use App\MasterPaymentMethod;
use App\MasterPaymentMethodDetail;
use App\Order;
use App\OrderDetail;
use App\OrderLog;
use App\OrderPayment;
use App\OrderPaymentLog;
use App\Payment;
use App\User;
use App\XenditBank;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Xendit\Xendit;
use App\CallbackXenditPayment;
use App\Helpers\Logistics;
use App\Helpers\Orders;
use App\OrderDetailLog;

class XenditController extends Api
{
//    private $token = 'xnd_production_ZisexzbehSCV696hJ0ZUGQZ9IjtSrHlFM0i8se0rnwYzlH78ybcHEETvWFnIa';
    private $token = 'xnd_production_7pjOlNPLibNZ3GEtMtf06tOdex3RbmsxYHV3CORUoYMDBzTBf1Xmdhuj0oGqz2';

    /**
     * @OA\Get(
     * path="/api/payment/xendit/bank/list",
     * summary="Payment",
     * description="get va list",
     * operationId="get va list",
     * tags={"Payment"},
     * security={ {"sanctum": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(

     *        )
     *     )
     * )
     */
    public function get_list_va(Request $request){
        Xendit::setApiKey($this->token);
        $getVABanks = \Xendit\VirtualAccounts::getVABanks();
        foreach ($getVABanks as $val){
            MasterPaymentMethodDetail::updateOrCreate([
                'code'   => $val['code'],
            ],
            [
                'payment_method_id'=>1,
                'name'=>$val['name'],
                'code'=>$val['code'],
                'is_activated'=>$val['is_activated'],
                'payment_gateway'=>'xendit',
                'created_at'=>date('Y-m-d H:i:s')
            ]);
        }
        return $this->successResponse($getVABanks);
    }

    /**
     * @OA\Post(
     * path="/api/payment/xendit/create/va",
     * summary="Payment",
     * description="create va list",
     * operationId="create va list",
     * tags={"Payment"},
     *     @OA\Parameter(
     *          name="bank_code",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="user_id",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="price",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     * security={ {"sanctum": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(

     *        )
     *     )
     * )
     */
    public function create_va(Request $request){
        $user = User::where('id',$request->user_id)->first();
        Xendit::setApiKey($this->token);
        $external_id = "va-".time();

        $params = [
            "external_id"=>$external_id,
            "bank_code"=>$request->bank_code,
            "name"=>$user->name,
            "expected_amount"=>$request->price,
            "is_closed"=>true,
            "expiration_date"=>Carbon::now()->addDays(1)->toISOString(),
            "is_single_true"=>true
        ];

        try {
            $create_va = \Xendit\VirtualAccounts::create($params);
            $insert = CallbackPayment::create([
                "external_id"=>$external_id,
                "payment_channel"=>"Virtual Account",
                "email"=>$user->email,
                "price"=>$request->price,
                "payment_gate"=>'Xendit',
            ]);
            return $this->successResponse($create_va);
        } catch (\Xendit\Exceptions\ApiException $e) {
            return $this->errorResponse(static::PAYMENT_FAILED,static::PAYMENT_FAILED_CODE);
        }
    }


    public function callback_va(Request $request){
        
        $external_id = $request->external_id;
        $update = CallbackPayment::create([
            'external_id'=>$request->external_id,
            'log'=> json_encode($request->all()),
            'payment_gate'=>'Xendit',
        ]);
        $order_payment = OrderPayment::where('external_id',$external_id)->first();
        if($order_payment) {
            $order_id = $order_payment->order_id;
            $order = Order::where('id',$order_id)
                ->update([
                    'status'=>2
                ]);
            $order_log = OrderLog::create([
                'order_id'=>$order_id,
                'status_id'=>2,
                'created_at'=>date('Y-m-d H:i:s')
            ]);

            $order_details_ = Orders::update_status_detail_by_order_id($order_id , 2);

            $order_payment_log = OrderPaymentLog::create([
                'external_id'=>$external_id,
                'order_id'=>$order_payment->order_id,
                'payload'=>'',
                'response'=>json_encode($request->all()),
                'created_at'=>date('Y-m-d H:i:s')
            ]);
            
            return $this->successResponse(static::PAYMENT_SUCCESS,static::PAYMENT_SUCCESS_CODE);
        }
        return $this->errorResponse(static::PAYMENT_FAILED,static::PAYMENT_FAILED_CODE);
    }


    /**
     * @OA\Get(
     * path="/api/payment/xendit/get/payment-method",
     * summary="Payment",
     * description="get payment-method list",
     * operationId="get payment-method list",
     * tags={"Payment"},
     * security={ {"sanctum": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(

     *        )
     *     )
     * )
     */
    
    public function get_payment_method(){
        $payment_method = MasterPaymentMethod::select('master_payment_methods.payment_name',
            'master_payment_method_details.name','master_payment_method_details.code','master_payment_method_details.is_activated')
            ->leftJoin('master_payment_method_details', 'master_payment_methods.id', '=', 'master_payment_method_details.payment_method_id')
            ->get();
        return $this->successResponse($payment_method);
    }

    public function webhook(Request $request){

        return Logistics::webhook($request->body());

    }



}
