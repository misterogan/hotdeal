<?php

namespace App\Http\Controllers\Api\merchant;

use App\Helpers\Hotpoint;
use App\Helpers\Logistics;
use App\Helpers\Notify;
use App\Helpers\Orders;
use App\HotpointSendLog;
use App\Http\Controllers\Api\Api;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderVendorResource;
use App\Jobs\CancelOrderAfterProcessed;
use App\Jobs\OperationMessage;
use App\Jobs\TransactionEmailNotification;
use App\Jobs\TransactionNotification;
use App\MasterStatusOrder;
use App\Order;
use App\OrderDetail;
use App\OrderDetailShipping;
use App\OrderDetailShippingTracker;
use App\OrderHistory;
use App\ProductDetail;
use App\Refund;
use App\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Yajra\DataTables\DataTables;

class OrderController extends Api
{
    static $SHIPPER_LABEL_SANDBOX = "https://sandbox.shipper.id/label/sticker.php";
    static $SHIPPER_LABEL_PRODUCTION = "https://shipper.id/label/sticker.php";

    /**
     * @OA\Patch(
     * path="/api/seller/order/update",
     * summary="OrderStatusUpdate",
     * description="Order Status Update to Processed",
     * operationId="UpdateOrder",
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
    public function update(Request $request) {
        // dd($request->all());
        $vendor = $this->vendor();
        $request->validate([
            'invoice_number' => 'required',
            'status_code' => 'required',
        ]);
        $reason = "";
        if($request->status_code == 'vendor_canceled'){
            if($request->reason == ''){
                return $this->errorResponse("Alasan penolakan harus diisi." , 301);
            }
            $reason = $request->reason;
        }
        $update = Orders::update_status($request->invoice_number, $vendor->vendor->id, $request->status_code , $reason);
        if ($update) { 
            //$order_detail = OrderDetail::where('id', $request->id)->with('order')->first();
            if($request->status_code == 'vendor_canceled'){
                TransactionNotification::dispatch($request->invoice_number , 'vendor_canceled');
                TransactionEmailNotification::dispatch($request->invoice_number , 'vendor_canceled');
                $order = OrderDetail::join('orders', 'order_details.order_id', 'orders.id')
                                       ->where('invoice_number', $request->invoice_number)->first();
                
                if($order->point > 0){
                    $sendPoint = Hotpoint::send($order->user_id , $order->point , 'EFC001' , 'Pengembalian hot point dari transaksi '.$order->transaction_number);
                    //MediaLog::create(['log' => 'prepare' , 'created_at' => date('Y-m-d')]);
                    if($sendPoint){
                        //MediaLog::create(['log' => 'send' , 'created_at' => date('Y-m-d')]);
                        Notify::send('Hotpoint' , 'Pengembalian hot point dari transaksi '.$order->transaction_number , '/hotpoint' , '' , 'uid' ,'hotpoint' , $order->user_id);

                        HotpointSendLog::create([
                            'amount'        =>$request->amount,
                            'email'         =>$order->user_id,
                            'description'   =>$request->description,
                            'created_by'    =>Auth::user()->name,
                            'created_at'    =>date('Y-m-d H:i:s')
                        ]);
                    }
                }
                Notify::send(Notify::$order_cancel_title , str_replace(
                    array("{name}", "{invoice_number}"),
                    array('Admin', $request->invoice_number),
                    Notify::$order_cancel_body_vendor),
                    '/admin/order' , '', 'uid' , Notify::$PAYMENT_TOPIC , '0', Notify::$PAYMENT_SEND_BY);
                    OperationMessage::dispatch(':x: Pesanan dengan nomor invoice ' . $request->invoice_number .' telah *DITOLAK* oleh merchant. *Alasan:* '.$reason);
            }
            if($request->status_code == 'processed'){
                TransactionEmailNotification::dispatch($request->invoice_number , 'accept');
                Notify::send(Notify::$ORDER_PROCESSED_TITLE , str_replace(
                    array("{name}", "{invoice_number}"),
                    array('Admin', $request->invoice_number),
                    Notify::$ORDER_PROCESSED_BODY),
                    '/admin/order' , '', 'uid' , Notify::$ORDER_PROCESSED_TITLE , '0', Notify::$PAYMENT_SEND_BY);
                    OperationMessage::dispatch(':white_check_mark: Pesanan dengan nomor invoice ' . $request->invoice_number .' telah *DIPROSES*  oleh merchant.');
                //CancelOrderAfterProcessed::dispatch($request->invoice_number)->delay(now()->addDays(2));
            }
            return $this->successResponse(['message' => "Berhasil memproses pemesanan."]);
        } else {
            return $this->errorResponse(401 , '');
        }
    }
    /**
     * @OA\Get(
     * path="/api/seller/order/list",
     * summary="OrderList",
     * description="Order List",
     * operationId="Order List",
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
    public function list(Request $request) {
       // print_r($request->all());
        $user = $this->user();
        $vendor = Vendor::where('user_id',$user->id)->first();
        $perpage = $request->perpage;
        $query = OrderDetail::where('vendor_id', $vendor->id)->with('order')->with('product');
        if($request->search != ''){
            $query->where('order_details.invoice_number' , 'LIKE' , '%'.$request->search.'%');
        }
        if($request->date != ''){
            $query->where('order_details.created_at', '>', $request->date);
        }
        if($request->filter != ''){
            $id_status = MasterStatusOrder::select('id')->where('status_code' , $request->filter)->where('status' ,'active')->first();
            $query->where('order_details.status' , $id_status->id);
        }
        $query->whereNotIn('status' , [1,10]);
        $query->orderBy('created_at' , 'DESC');
        $order = $query->paginate($perpage);
        $data['tracker'] = ['pending'=> 'Proses penjual', 'delivery' => 'dikirim', 'arrived' => 'Pesanan Sampai'];
        if($order){
            $data['orders'] = OrderVendorResource::collection($order);
            $data['current_page'] = $order->currentPage();
            $data['total'] = $order->lastPage();
            return $this->successResponse($data);
        }
        else{
            return $this->successResponse([]);
        }
    }
    public function byinvoice(Request $request){
        $user = $this->user();
        $vendor = Vendor::where('user_id',$user->id)->first();
        $data = OrderDetail::select( 'order_details.invoice_number','order_details.id',
        'order_details.invoice_total_payment',
        'order_details.status as status_code',
        'orders.point',
        'order_details.invoice_total_discount as total_discount',
        'order_details.created_at',
        'master_status_order.description',
        'order_payments.bank_code',
        'order_payments.account_number',
        'payment_methods.label as payment_channel',
        'payment_methods.channel',
        'order_detail_shipping.shipping_cost',
        'order_detail_shipping.consignee',
        'order_detail_shipping.consigner',
        'order_detail_shipping.logistic_detail',
        'order_detail_shipping.awb_number',
        'order_detail_shipping.rate',
        )->join('orders' , 'orders.id' , 'order_details.order_id')
        ->leftJoin('order_detail_shipping' , 'order_detail_shipping.order_details_id' , 'order_details.id')
        ->leftJoin('master_status_order' , 'order_details.status' , 'master_status_order.id')
        ->leftJoin('order_payments' , 'order_payments.order_id' , 'orders.id')
        ->leftJoin('payment_methods' , 'order_payments.bank_code' , 'payment_methods.code')
        ->with('productswithdetail')
        ->with('order_logs')
        ->where('order_details.invoice_number' , $request->invoice)
        //->where('orders.user_id' , Auth::user()->id)
        ->first();
       // echo $data->created_at = date('Y-m-d H:i' , strtotime($data->created_at)); exit;
        if($data){
            $data->tracking = $this->get_tracking($data->id);
            $data->order_log_step = $this->logs_order($data->order_logs);
            $data->transaction_date = date('Y-m-d H:i:s' , strtotime($data->created_at));
            $data->tracker = ['pending'=> 'Proses penjual', 'delivery' => 'dikirim','arrived' => 'Pesanan Sampai'];
        }
        return $this->successResponse($data);

    }
    
    function logs_order($data){
        $response = [];
        foreach($data as $item){
            $response[$item->master_status->status_code] = $item->master_status->description;
        }
        return $response;
    }

    public function create_pickup(Request $request){
        $order_id = OrderDetailShipping::select('order_detail_shipping.order_id', 'order_details.id as detail_id', 'estimasi')->join('order_details' ,'order_detail_shipping.order_details_id' , 'order_details.id')->where('order_details.invoice_number' , $request->invoice_number)->first();

        $today = Carbon::now();
        $estimasi = $order_id->estimasi;
        $estimasi = $estimasi == null ? '3' : $estimasi;
        if(strlen($estimasi) > 2){
            $splitEst = explode(' - ', $estimasi);
            $estimasi = $today->addDay($splitEst[0])->format('d').' - '.$today->addDay($splitEst[1])->format('d M');
        } else {
            $estimasi = $today->addDay($estimasi)->format('d M');
        }

        $order = OrderDetail::where('id', $order_id->detail_id)->pluck('order_id')->first();
        $user_id = Order::where('id', $order)->pluck('user_id')->first();
        
        if(!$order_id){
            return $this->errorResponse('Tidak dapat memproses penjemputan' , 201);
        }
        $update_order = Logistics::createPickupLogistic('SHIPPER' ,$order_id->order_id);
        if(!$update_order){
            return $this->errorResponse('Tidak dapat memproses penjemputan' , 201);
        }
        
        if($estimasi){
            Notify::send(
                'Estimasi barang sampai', 
                'Pesanan Anda akan diterima pada tanggal ' . $estimasi, 
                '/notification', 
                '', 
                'uid', 
                'Estimasi', 
                $user_id
            );
        }


        
        OperationMessage::dispatch(':package: Merchant telah meminta penjemputan untuk pesanan dengan nomor invoice ' . $request->invoice_number);

        return $this->successResponse([] , 'Permintaan Penjemputan berhasil');
    }

    public function get_tracking($id){
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

    public function label(Request $request){
        $validation = Validator::make($request->all(), [
            'invoice' => 'required',
        ]);
        if ($validation->fails()) {
            return $this->errorResponse('Tidak dapat diproses',201);
        }
        // check label 
        $order = OrderDetail::leftJoin('order_detail_shipping' , 'order_details.id' ,'order_detail_shipping.order_details_id')->where('order_details.invoice_number' , $request->invoice)->first();
        $label = $order->label;
        if(!$label){
            // get label id
            $label = Logistics::shipperGetOrder($order->order_id);
        }
        if(!$label){
            return $this->errorResponse('Error' , 201);
        }
        //config('app.env') == 'DEVELOPMENT' ? self::$API_KEY_SANBOX : self::$API_KEY_PRODUCTION
        $url = config('app.env') == 'production' ? self::$SHIPPER_LABEL_PRODUCTION :self::$SHIPPER_LABEL_SANDBOX;
        return $this->successResponse($url."?oid[]={$order->order_id}&uid={$label}");
    }

      
    public function cancel_order(Request $request){
        $vendor = $this->vendor();
        $request->validate([
            'invoice_number' => 'required',
            'status_code' => 'required',
        ]);
    }

}
