<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessExpiredOrder;
use App\Order;
use App\OrderDetail;
use App\Refund;
use App\RefundStatus;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\MasterStatusOrder;
use App\OrderDetailLog;
use App\OrderDetailShippingTracker;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index(Request $request){
        $status = MasterStatusOrder::where('status_code', '!=', 'completed')
                    ->where('status_code', '!=', 'user_canceled')
                    ->where('status_code', '!=', 'vendor_canceled')->get();
        return view('admin.order.index', compact('status'));
    }

    public function edit_view(Request $request) {
        $invoice_number = Utils::unslugify($request->slugged_invoice_number);
        $order = OrderDetail::select( 'order_details.invoice_number','order_details.id',
        'order_details.invoice_total_payment',
        'order_details.invoice_total_discount',
        'order_details.created_at',
        'order_details.order_id',
        'order_details.status',
        'order_details.vendor_id',
        'order_details.insurance_fee',
        'master_status_order.description',
        'order_payments.bank_code',
        'payment_methods.label as payment_channel',
        'payment_methods.channel',
        'orders.point',
        'orders.status as order_status',
        'orders.created_at as order_created',
        'orders.transaction_number',
        'orders.total_discount',
        'orders.total_payment',
        'order_payments.account_number',
        'order_detail_shipping.shipping_cost',
        'order_detail_shipping.consignee',
        'order_detail_shipping.consigner',
        'order_detail_shipping.awb_number',
        'order_detail_shipping.logistic_detail',
        'order_detail_shipping.pickup_code',
        'order_detail_shipping.rate',
        'order_detail_shipping.order_id as order_logistic_id',
        'order_vouchers.detail_voucher',
        'order_vouchers.voucher_value'
        )->join('orders' , 'orders.id' , 'order_details.order_id')
        ->leftJoin('order_vouchers' , 'order_vouchers.order_id' , 'orders.id')
        ->leftJoin('order_detail_shipping' , 'order_detail_shipping.order_details_id' , 'order_details.id')
        ->leftJoin('master_status_order' , 'orders.status' , 'master_status_order.id')
        ->leftJoin('order_payments' , 'order_payments.order_id' , 'orders.id')
        ->leftJoin('payment_methods' , 'order_payments.bank_code' , 'payment_methods.code')
        ->with('vendor')
        ->with('productswithdetail')
        ->where('invoice_number', $invoice_number)
        ->with('status_order')
        ->first();
        if($order){
            //$order->transaction_date = date('Y-m-d H:i:s' , strtotime($order->created_at));
            $order->tracking = $this->get_tracking($order->id);
            //$order->total_discount = $this->calculate_total_discount($daorderta->order_id ,$data->total_discount );//$this->get_tracking($data->id);
        }

        $status = MasterStatusOrder::where('status', 'active')->orderBy('id')->get();
        $logs = OrderDetailLog::where('order_details_id', $order->id)->orderByDesc('id')->get();
        return view('admin.order.edit', compact('order', 'status', 'logs'));
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
                        'code' => $v->code,
                        'description' => $description->external->description,
                        'date' => date('d , F Y' , strtotime($v->created_at)),
                    ];
                }
                $code= $v->code;
            }
        }
        return $response;
    }

    public function order_dt(Request $request) {
        $order = OrderDetail::with('order_products')
            ->with('order')  
            ->with('vendor')
            ->with('master_status')
            ->with('productswithdetail')
            ->with('product')
            ->orderByDesc('created_at');

        if($request->end_date && $request->start_date != ''){
            $startDate = $request->start_date;
            $endDate = $request->end_date; 
            $order->whereBetween('created_at', [$startDate, $endDate]);
        } 
        if($request->filter_id && $request->filter != ''){
            $id = $request->filter_id;
            $filter = $request->filter;

            if($id == 1){
                $order->transaksiFilter($filter);
            } else if($id == 2){
                $order->where('invoice_number' , 'LIKE' , '%'.$filter.'%');
            } else if($id == 3){
                $order->productName($filter);
            } else if($id == 4){
                $order->vendor($filter);
            } else if($id == 5){
                // Payment Method
            }
        }

        if(strtolower($request->status_value) == 'semua'){
            $order;
        } else if($request->status_value == 'Order Complete'){
            $filter = $request->status_value;
            $order->where('status', 12)->orWhere('status', 5);
        } else if($request->status_value == 'Pesanan Dibatalkan'){
            $filter = $request->status_value;
            $order->where('status', 8)->orWhere('status', 9);
        }
        else if($request->status_value != ''){
            $filter = $request->status_value;
            $order->statusOrder($filter);
        }
        
        return DataTables::of($order->get())->addIndexColumn()->make(true);
    }

    public function edit(Request $request) {
        $now = Carbon::now();
        $admin = Auth::user();
        $validation = Validator::make($request->all(), [
            'status' => 'required',
        ]);

        if ($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $order_detail = OrderDetail::findOrFail($request->detail_id);

        $order_detail->status = $request->status;
        $order_detail->updated_at = $now;

        if ($order_detail->save()) {
            OrderDetailLog::create([
                'order_details_id' => $order_detail->id,
                'status' => $order_detail->status,
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' => $admin->name,
                'updated_by' => $admin->name
            ]);
            return json_encode(['status'=> true, 'message'=> 'Success']);
        } else {
            return json_encode(['status'=> false, 'message'=> 'Failed']);
        }
    }

    public function log_dt(Request $request) {
        $invoice_number = str_replace('-','/', $request->invoice_number);
        $order_detail = OrderDetail::where(DB::raw('lower(invoice_number)'), $invoice_number)->first();
        $logs = OrderDetailLog::with('master_status')->where('order_details_id', $order_detail->id);
        
        return DataTables::of($logs->get())->addIndexColumn()->make(true);
    }

    public function order_canceled(Request $request){
        return view('admin.order.canceled.index');
    }

    public function order_canceled_dt(Request $request){
        $order = OrderDetail::select('order_details.*','users.name','order_detail_logs.description as order_logs_desc',
            'order_detail_logs.created_at as date','refunds.created_at as refund_date','refunds.refund_type')
            ->leftjoin('orders','order_details.order_id','=', 'orders.id')
            ->leftjoin('users','orders.user_id','=','users.id')
            ->leftjoin('order_detail_logs','order_details.id','=','order_detail_logs.order_details_id')
            ->leftjoin('refunds','order_details.id','=','refunds.order_details_id')
            ->where('order_detail_logs.status',9)->whereIn('order_details.status',[9,10,11])->get();
        return DataTables::of($order)->addIndexColumn()->make(true);
    }

    public function cancel_detail(Request $request) {

        $invoice_number = Utils::unslugify($request->invoice_number);
        $order = OrderDetail::where('invoice_number', $invoice_number)
            ->with('order_products.product_detail.product')
            ->with('order.user.user_addresses')
            ->with('vendor.province')
            ->with('shipping')
            ->with('payment')
            ->with('master_status')
            ->first();

        $refund = Refund::where('order_details_id', $order->id)
            ->with('status')
            ->with('user')
            ->with('logs')
            ->with('bank_account')
            ->with('refund_confirmation')
            ->with('transaction')->orderByDesc('created_at')->first();

        $refund_status = RefundStatus::orderBy('id')->get();

        return view('admin.order.canceled.detail', compact('order',  'refund','refund_status'));
    }


}
