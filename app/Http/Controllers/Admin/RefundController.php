<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Emails;
use App\Helpers\Notify;
use App\Helpers\Utils;
use App\Hotpoint;
use App\Http\Controllers\Controller;
use App\OrderDetail;
use App\Refund;
use App\RefundLogs;
use App\RefundReturnConfirmation;
use App\RefundStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RefundController extends Controller
{
    public function index(Request $request){
        return view('admin.refund.index');
    }

    public function edit_view(Request $request) {
        $invoice_number = Utils::unslugify($request->invoice_number);
        $order = OrderDetail::where('invoice_number', $invoice_number)
            ->with('order_products.product_detail.product')
            ->with('order.user.user_addresses')
            ->with('vendor.province')
            ->with('shipping')
            ->with('payment')
            ->with('master_status')
            ->first();

        $refund = Refund::where('id', $request->id)
        ->with('status')
        ->with('user')
        ->with('logs')
        ->with('bank_account')
        ->with('refund_confirmation')
        ->with('transaction')->orderByDesc('created_at')->first();

        $refund_status = RefundStatus::orderBy('id')->get();

        return view('admin.refund.edit', compact('order', 'refund', 'refund_status'));
    }

    public function refund_dt() {

        $refund = Refund::select('refunds.*','refunds.id as refund_id','users.id as user_id','users.name','order_details.invoice_number','refund_status.status as refund_status')
        ->leftjoin('users','refunds.user_id','=', 'users.id')
        ->leftjoin('order_details','refunds.order_details_id','=', 'order_details.id')
        ->leftjoin('refund_status','refunds.refund_status_id','=', 'refund_status.id')
            ->where('order_details.status',6)
            ->orderByDesc('refunds.created_at');
        return DataTables::of($refund->get())->addIndexColumn()->make(true);
    }

    public function update_status(Request $request) {
        // dd($request->all());
        $validation = Validator::make($request->all(), [
            'refund_id' => 'required',
            'refund_status' => 'required',
            'refund_type' => 'required',
        ]);

        if ($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $refund = Refund::where('id', $request->refund_id)->with('transaction')->first();
        $status = RefundStatus::where('id', $request->refund_status)->first();
        $receipt_number = RefundReturnConfirmation::where('refund_id', $request->refund_id)->value('receipt_number');
        $refund->refund_status_id = $request->refund_status;
        // dd($refund->detail->vendor->user_id);
        if ($request->refund_status == 5 && $request->refund_type == 'hotpoint') {
            if ($request->has('hotpoint') || $request->hotpoint > 0) {
                // logic to give Hotpoint
                // $hotpoints = \App\Helpers\Hotpoint::send($refund->user_id, $refund->transaction->invoice_total_payment, 'EFREFUND', 'refund ' . $refund->transaction->invoice_number);
                $hotpoints = \App\Helpers\Hotpoint::send($refund->user_id, $request->hotpoint, 'EFREFUND', 'refund ' . $refund->transaction->invoice_number);

                if (!$hotpoints) {
                    return json_encode(['status'=> false, 'message'=> ['Something went wrong.']]);
                }
            }
            // USER NOTIFICATION
            Notify::send(
                'Pengembalian selesai', 
                'Pengembalian dana berhasil dan proses refund untuk pesanan  ' . $refund->detail->invoice_number . ' sudah selesai', 
                '/hotpoint', 
                '', 
                'uid', 
                'Refund', 
                $refund->user_id
            );
            // VENDOR NOTIFICATION
            Notify::send(
                'Pengembalian selesai', 
                'Pengembalian dana berhasil dan proses refund untuk pesanan  ' . $refund->detail->invoice_number . ' sudah selesai', 
                '/', 
                '', 
                'uid', 
                'Refund', 
                $refund->detail->vendor->user_id
            );
            // ADMIN NOTIFICATION
            Notify::send(
                'Pengembalian selesai', 
                'Pengembalian dana berhasil dan proses refund untuk pesanan  ' . $refund->detail->invoice_number . ' sudah selesai', 
                '/admin/refunds', 
                '', 
                'uid', 
                'Refund', 
                '0'
            );
        }

        if ($refund->save()) {
            RefundLogs::create([
                'refund_id' => $refund->id,
                'refund_status_id' => $status->id,
                'description' => $status->description,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            if ($status->id == 2){
                // VENDOR NOTIFICATION
                Notify::send(
                    Notify::$REFUND_CONFIRM_TITLE 
                    ,'Pengajuan refund untuk nomor pesanan '. $refund->detail->invoice_number .' telah dikonfirmasi dan akan diproses'
                    , '/notification' 
                    , ''
                    , 'uid' 
                    , Notify::$REFUND_TOPIC
                    ,$refund->detail->vendor->user_id
                    ,'System'
                );
                // USER NOTIFICATION
                Notify::send(
                    Notify::$REFUND_CONFIRM_TITLE 
                    ,'Pengajuan refund untuk nomor pesanan '. $refund->detail->invoice_number .' telah dikonfirmasi dan akan diproses'
                    , '/notification' 
                    , ''
                    , 'uid' 
                    , Notify::$REFUND_TOPIC
                    ,$refund->user_id
                    ,'System'
                );
                // ADMIN NOTIFICATION
                Notify::send(
                    Notify::$REFUND_CONFIRM_TITLE 
                    ,'Pengajuan refund untuk nomor pesanan '. $refund->detail->invoice_number .' telah dikonfirmasi dan akan diproses'
                    , '/notification' 
                    , ''
                    , 'uid' 
                    , Notify::$REFUND_TOPIC
                    ,'0'
                    ,'System'
                );
            }
            if ($status->id == 5){
                Notify::send(
                    'Pengembalian dana diproses', 
                    'Pengembalian dana untuk pengajuan ' . $refund->detail->invoice_number . ' telah terkonfirmasi dan akan diproses', 
                    '/', 
                    '', 
                    'uid', 
                    'Refund', 
                    $refund->detail->vendor->user_id
                );
            }
            if ($status->id == 6){
                $order = OrderDetail::findOrFail($refund->order_details_id);
                if($order){
                    $order->status = 7;
                    $order->save();
                }
            }
//            Notify::send('Refund', $status->description, '/', '', $refund->user_id, 'Refund', $refund->user_id);

            return json_encode(['status'=> true, 'message'=> 'Success']);
        } else {
            return json_encode(['status'=> false, 'message'=> ['Something went wrong.']]);
        }
    }

    public function approve(Request $request) {
        $validation = Validator::make($request->all(), [
            'refund_id' => 'required',
        ]);

        if ($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->messages()]);
        }

        $refund = Refund::where('refund_id', $request->refund_id)->with('status')->with('user')->with('transaction')->first();

        if ($refund) {
            $user_id = $refund->user_id;
            $amount = $refund->transaction->invoice_total_payment;
            $refund_type = $refund->refund_type;

            if ($refund_type == 'hotpoint') {
                // Refund Hotpoint logic here
            } else {
                // Refund cash logic here
            }

            return json_encode(['status'=> true, 'message'=> 'Success']);
        } else {
            return json_encode(['status'=> false, 'message'=> ['Something went wrong.']]);
        }
    }
}
