<?php

namespace App\Http\Controllers\Api;

use App;
use App\Helpers\Logistics;
use App\Helpers\Notify;
use App\Helpers\Orders;
use App\Helpers\RejekiNomplok;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderDetailCancelProductResource;
use App\Http\Resources\OrderDetailResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderTransactionResource;
use App\Jobs\InviteReward;
use App\Jobs\OperationMessage;
use App\Jobs\TotalPurchase;
use App\Jobs\TransactionEmailNotification;
use App\MasterStatusOrder;
use App\Order;
use App\OrderDetail;
use App\OrderDetailShippingTracker;
use App\OrderHistory;
use App\OrderPayment;
use App\OrderPaymentLog;
use App\TestingJob;
//use Barryvdh\DomPDF\PDF;
use Barryvdh\DomPDF\Facade as PDF;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class TransactionController extends Api
{
    /**
     * @OA\Post(
     * path="/api/transaction/unpaid/get/list",
     * summary=" Transaction",
     * description="get order by user",
     * operationId="get order by user",
     * tags={"Transaction"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(
     *        )
     *     )
     * )
     */
    public function get(Request $request){
        $user = $this->user();
        $orders = Order::with('order_detail','order_payments',
        'order_detail.order_products',
        'order_detail.order_products.gallery',
        'order_detail.order_products.product_detail',
        'order_detail.shipping')->where('user_id',$user->id);
        if($request->filter == 'all'){
            $orders->whereIn('status', [1, 10]);
        }
        if($request->filter == 'expired'){
            $orders->where('orders.status',10);
        }

        $date = Carbon::today()->subDays(2);
        $now = Carbon::now();
        Order::where('user_id', $user->id)->where('expired_date', '<=', $now)->update(
            array('status' => 10)
        );
        $data = $orders->where('created_at', '>=' , $date)->orderBy('orders.id' , 'DESC')->paginate();

        if($data){
            $response['current_page'] = $data->currentPage();
            $response['total'] = $data->total();
        }

        $response['orders'] =  OrderResource::collection($data);
        return $this->successResponse($response);
    }


    /**
     * @OA\Post(
     * path="/api/transaction/unpaid/status/get/",
     * summary=" Transaction",
     * description="get order by status",
     * operationId="get order by status",
     * tags={"Transaction"},
     * security={ {"bearer": {} }},
     *     @OA\Parameter(
     *          name="status_code",
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
    public function get_transaction_by_status(Request $request){
        $user = $this->user();
        $status = MasterStatusOrder::where('status_code',$request->status_code)->first();
        $status_id = $status->id;
        $orders = Order::with('order_detail','order_payments',
            'order_detail.order_products',
            'order_detail.order_products.gallery',
            'order_detail.order_products.product_detail',
            'order_detail.shipping')
            ->where('user_id',$user->id)
            ->where('status',$status_id)->paginate();
        if(!$orders){
            $orders = [];
        }
        $response['orders'] =  OrderResource::collection($orders);
        $response['total_order'] = count($orders);
        $response['last_transaction_at'] = '28 /12 / 2021';
        return $this->successResponse($response);
    }

    /**
     * @OA\Post(
     * path="/api/transaction/unpaid/get/detail",
     * summary=" Transaction",
     * description="get transaction detail",
     * operationId="get transaction detail",
     * tags={"Transaction"},
     * security={ {"bearer": {} }},
     *     @OA\Parameter(
     *          name="transaction_number",
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
    public function detail(Request $request){
        $transaction_number = $request->transaction_number;
        $order = Order::with('order_payments','order_history')->where('orders.transaction_number',$transaction_number)->first();
        $response['order'] = new OrderTransactionResource($order);
        return $this->successResponse($response);
    }

    /**
     * @OA\Post(
     * path="/api/transaction/paid/get/list",
     * summary=" Transaction",
     * description="get order paid by user",
     * operationId="get order paid by user",
     * tags={"Transaction"},
     * security={ {"bearer": {} }},
     *     @OA\Parameter(
     *          name="status_code",
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
    public function get_per_invoice(Request $request){
        $user = $this->user();
        if(!$user){
            return $this->successResponse([]);
        }
        if($request->filter != ''){
            $status = MasterStatusOrder::where('status_code',$request->filter)->first();
            $data = OrderDetail::select('order_details.*','orders.total_payment','orders.point','orders.total_discount')->leftjoin('orders','order_details.order_id','orders.id');
            if($status){
                if($request->filter == 'all'){
                    //$order_details->where();
                }
                if($request->filter == 'completed'){
                    $data->where('order_details.status',$status->id);
                }

                if($request->filter == 'processed'){
                    $data->whereIn('order_details.status', ['2', '3']);
                }
                if($request->filter == 'refunded'){
                    $data->whereIn('order_details.status',[$status->id , 6]);
                }   
            }
            if($request->filter == 'cancelled'){
                $data->whereIn('order_details.status', [8,9,10]);
            }
            $order_details =    $data->with('status_order')
                                ->with('order_logs')
                                ->with('shipping')
                                ->with('review')
                                ->where('orders.user_id',$user->id)
                                ->whereNotIn('orders.status', [1,10])
                                ->orderBy('orders.id' , 'DESC')
                                ->paginate(10);
        }
        else{
            $order_details = OrderDetail::select('order_details.*','orders.total_payment','orders.point','orders.total_discount')->leftjoin('orders','order_details.order_id','orders.id')
            ->with('status_order')
            ->with('order_logs')
            ->with('shipping')
            ->where('orders.user_id',$user->id)
            ->whereNotIn('orders.status', [1,10])
            ->orderBy('orders.id' , 'DESC')
            ->paginate(10);
        }

        if($order_details){
            $response['current_page'] = $order_details->currentPage();
            $response['total'] = $order_details->lastPage();
        }
        $response['order_details'] =  OrderDetailResource::collection($order_details);
        $response['tracker'] = ['pending'=> 'Proses penjual', 'delivery' => 'dikirim','arrived' => 'Pesanan Sampai'];
        //$response['trackings'] = '';
        return $this->successResponse($response);
    }

    public function invoice_modal(Request $request) {
        $user = $this->user();

        if ($user == null) {
            return $this->errorResponse(self::ERROR_NOT_AUTHORIZED, 404);
        }

        $data = OrderDetail::with(['vendor', 'order_products', 'status_order', 'order.user', 'order.payment.paymentMethod', 'productswithdetail', 'shipping.services', 'refund'])
            ->where('invoice_number', $request->invoice)
            ->whereHas('order', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->first();
        if($data){
            $data->transaction_date = date('Y-m-d H:i:s' , strtotime($data->order->created_at));
        }
        if($data->order){
            $data->order->total_discount = $this->calculate_total_discount($data->order_id , $data->order->total_discount);
        }
        // dd($data);

        return $this->successResponse($data);
    }

    function calculate_total_discount($order_id , $amount_discount){
        $total_transaction = OrderDetail::where('order_id' , $order_id)->count();
        if($total_transaction > 0){
            return floor($amount_discount / $total_transaction);
        }
        return $amount_discount;
        
    }

    public function byinvoice(Request $request){
        $user = $this->user();

        if ($user == null) {
            return $this->errorResponse(self::ERROR_NOT_AUTHORIZED, 404);
        }
        $data = OrderDetail::select( 'order_details.invoice_number','order_details.id',
        'order_details.invoice_total_payment',
        'order_details.invoice_total_discount',
        'order_details.insurance_fee',
        'order_details.created_at',
        'order_details.order_id',
        'order_details.status as status_code',
        'master_status_order.description',
        'order_payments.bank_code',
        'payment_methods.label as payment_channel',
        'payment_methods.channel',
        'orders.point',
        'orders.total_discount',
        'order_payments.account_number',
        'order_detail_shipping.shipping_cost',
        'order_detail_shipping.consignee',
        'order_detail_shipping.consigner',
        'order_detail_shipping.awb_number',
        'order_detail_shipping.logistic_detail',
        'order_detail_shipping.rate',
        )->join('orders' , 'orders.id' , 'order_details.order_id')
        ->leftJoin('order_detail_shipping' , 'order_detail_shipping.order_details_id' , 'order_details.id')
        ->leftJoin('master_status_order' , 'order_details.status' , 'master_status_order.id')
        ->leftJoin('order_payments' , 'order_payments.order_id' , 'orders.id')
        ->leftJoin('payment_methods' , 'order_payments.bank_code' , 'payment_methods.code')
        ->with('vendor')
        ->with('productswithdetail')
        ->where('order_details.invoice_number' , $request->invoice)
        ->where('orders.user_id' , Auth::user()->id)->first();

        if($data){
            $data->transaction_date = date('Y-m-d H:i:s' , strtotime($data->created_at));
            $data->tracking = $this->get_tracking($data->id);
            $data->total_discount = $this->calculate_total_discount($data->order_id ,$data->total_discount );//$this->get_tracking($data->id);
        }
        return $this->successResponse($data);
    }

    function get_tracking($id){
        $tracking = OrderDetailShippingTracker::select('tracker','code','created_at')->where('order_detail_id' , $id)->orderBy('created_at' , 'DESC')->get();
        //return $tracking;
        $response = [];
        if($tracking){
            $code = '';
            foreach($tracking as $k=>$v){
                if($code != $v->code){
                    $description = json_decode($v->tracker);
                    $response[] = [
                        'time' => date('H:i' , strtotime($v->created_at)),
                        //'description' => Logistics::$LOGISTIC_STATUS['SHIPPER'][$v->code],
                        'description' => $description->external->description,
                        'date' => date('d , F Y' , strtotime($v->created_at)),
                    ];
                }
                $code= $v->code;
            }
        }
        return $response;
    }

    public function complete_order(Request $request){
        if(!Auth::check()){
            return $this->errorResponse(self::ERROR_NOT_LOGIN, self::ERROR_NOT_LOGIN_CODE);
        }
        $user = Auth::user(); 
        $validation = Validator::make($request->all(), [
            'invoice'         => 'required'
        ]);
        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),'201');
        }
        // Rejeki Nomplok Logic Here
        DB::beginTransaction();
        DB::enableQueryLog();

        try {
            RejekiNomplok::create_coupon($request->invoice, $user->id);
            DB::commit();
        } catch (\Throwable $e) {
            // Log::channel('errorlog')->info(['module'=> 'Order', 'message' => $e->getMessage(), 'query' => $queries]);
            DB::rollback();
            return false;
        }
        // End of Rejeki Nomplok logic

        $update_transaction_status = Orders::update_status_by_customer($request->invoice , 'arrived' ,'completed');
        // $update_transaction_status = true;
        if($update_transaction_status){
            
            TotalPurchase::dispatch($request->invoice);
            Notify::send(Notify::$ORDER_COMPLETED_TITLE , str_replace(
                array("{name}", "{invoice_number}"),
                array('Admin', $request->invoice),
                Notify::$ORDER_COMPLETED_BODY),
                '/admin/order' , '', 'uid' , Notify::$ORDER_COMPLETED_TITLE , '0', Notify::$PAYMENT_SEND_BY);
            InviteReward::dispatch($request->invoice)->delay(now()->addMinutes(1));
            return $this->successResponse();
        }

        return $this->errorResponse(self::ERROR_WHEN_SAVE_DATA,self::ERROR_WHEN_SAVE_DATA_CODE);
    }

    public function download_invoice(Request $request) {

        $invoice_number = $request->invoice_number;
        
        $data = OrderDetail::select( 'order_details.invoice_number','order_details.id',
        'order_details.invoice_total_payment',
        'order_details.invoice_total_discount',
        'order_details.vendor_id',
        'order_details.created_at',
        'order_details.updated_at',
        'master_status_order.description',
        'order_payments.bank_code',
        'payment_methods.label',
        'orders.point',
        'orders.id as order_id',
        'orders.total_discount',
        'order_payments.account_number',
        'order_detail_shipping.shipping_cost',
        'order_detail_shipping.consignee',
        'order_detail_shipping.consigner',
        'order_detail_shipping.awb_number',
        'order_detail_shipping.logistic_detail',
        'order_detail_shipping.rate',
        )->join('orders' , 'orders.id' , 'order_details.order_id')
        ->leftJoin('order_detail_shipping' , 'order_detail_shipping.order_details_id' , 'order_details.id')
        ->leftJoin('master_status_order' , 'order_details.status' , 'master_status_order.id')
        ->leftJoin('order_payments' , 'order_payments.order_id' , 'orders.id')
        ->leftJoin('payment_methods' , 'order_payments.bank_code' , 'payment_methods.code')
        ->with('vendor')
        ->with('productswithdetail')
        ->where('order_details.invoice_number' , $invoice_number)
        ->where('orders.user_id' , Auth::user()->id)
        ->first();
        $voucher_split = OrderDetail::where('order_id' , $data->order_id)->get()->count(); 
        $pdf = PDF::loadView('invoice.invoice', ['data' => $data , 'voucher_split' => $voucher_split]);
        
        return $pdf->download($data->invoice_number.'.pdf');
    }

    public function continue_payment(Request $request){
        $transaction =  Order::select('orders.*' ,'order_payment_logs.response')->join('order_payment_logs' , 'order_payment_logs.order_id' , 'orders.id')
                        ->where('orders.transaction_number' , $request->transaction_id)
                        ->where('order_payment_logs.action' , 'create')
                        ->where('orders.status' , 1)->first();
        if(!$transaction){
            return $this->errorResponse(static::TRANSACTION_NOT_FOUND ,401);
        }
        $res = json_decode($transaction->response);
        return $this->successResponse($res->actions->desktop_web_checkout_url);
    }

    public function cancel_order_by_invoice(Request $request){
        $user = $this->user();
        $result = [];
        
        if ($user == null) {
            return $this->errorResponse(self::ERROR_NOT_AUTHORIZED, 404);
        }
        $orderDetail = OrderDetail::select('order_details.*')
                        ->join('orders' , 'orders.id' ,'order_details.order_id')
                        ->where('order_details.invoice_number' , $request->invoice)
                        ->where('orders.user_id' , $user->id)
                        ->first();
       $request = [];
       if($orderDetail){
            $result = new OrderDetailCancelProductResource($orderDetail);
       }
       //print_r($result); 
       return $this->successResponse($result);
    }
}
