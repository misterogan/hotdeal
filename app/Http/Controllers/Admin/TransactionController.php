<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Notify;
use App\Http\Controllers\Controller;
use App\MasterStatusOrder;
use App\Order;
use App\OrderDetail;
use App\OrderDetailProduct;
use App\OrderDetailShippingTracker;
use App\OrderPayment;
use App\OrderVouchers;
use App\Product;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    public function index(Request $request){
        return view('admin.transaction.index');
    }

    public function edit_view(Request $request) {

        $data2 = OrderDetail::select( 'order_details.invoice_number','order_details.id',
        'order_details.invoice_total_payment',
        'order_details.invoice_total_discount',
        'order_details.created_at',
        'order_details.order_id',
        'order_details.status',
        'order_details.vendor_id',
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
        ->where('orders.transaction_number' , $request->transaction_number)
        ->with('status_order')
        ->get();
        // dd($data2);
        
        $transaction_number = $request->transaction_number;
        $order = Order::where('transaction_number', $transaction_number)
            ->with('user.user_addresses.province')
            ->with('master_status')
            ->with('order_detail.master_status')
            ->with('voucher')
            ->first(); 
        $status = MasterStatusOrder::where('id', $data2[0]->status)->pluck('description')->first();
        return view('admin.transaction.edit', compact('data2' ,'order', 'status'));
    }

    

    public function transaction_dt() {
        $transaction = Order::with('user')->with('master_status')->orderByDesc('created_at');

        return DataTables::of($transaction->get())->addIndexColumn()->make(true);
    }

    public function change_status(Request $request) {
        $validation = Validator::make($request->all(), [
            'transaction_number' => 'required',
            'status_code' => 'required',
        ]);

        if ($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $transaction = Order::where('transaction_number', $request->transaction_number)->first();
        $status = MasterStatusOrder::where('status_code', $request->status_code)->value('id');

        $transaction->status = $status;
        $transaction->updated_at = Carbon::now();

        if ($transaction->save()) {
            return json_encode(['status'=> true, 'message'=> 'Success']);
        } else {
            return json_encode(['status'=> false, 'message'=> 'Failed']);
        }
    }

    public function edit(Request $request) {
        $validation = Validator::make($request->all(), [
            'user_id' => 'required',
            'status' => 'required',
        ]);

        if ($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $admin = Auth::user();
        $user = User::where('id', $request->user_id)->first();

        $user->status = $request->status;
        $user->updated_by = $admin->name;
        $user->updated_at = Carbon::now();

        if ($user->save()) {
            return json_encode(['status'=> true, 'message'=> 'Success']);
        } else {
            return json_encode(['status'=> false, 'message'=> 'Failed']);
        }
    }
}
