<?php

namespace App\Http\Controllers\Api\merchant;

use App\Helpers\Refunds;
use App\Http\Controllers\Api\Api;
use App\Http\Resources\OrderVendorResource;
use App\MasterStatusOrder as AppMasterStatusOrder;
use App\Order;
use App\OrderDetail;
use App\OrderDetailShippingTracker;
use App\Refund;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use MasterStatusOrder;

class RefundController extends Api
{
    public function refund_seller_list(Request $request) { 
        $user = $this->user();

        if (!$user->is_vendor) {
            return $this->errorResponse(self::ERROR_NOT_AUTHORIZED, self::ERROR_NOT_LOGIN_CODE);
        }
        $vendor = Vendor::where('user_id',$user->id)->first();
        $query = OrderDetail::where('vendor_id', $vendor->id)
                ->with('order')
                ->with('product')
                ->with('refund');
        $query->whereIn('status' , [6,7])
        ->orderBy('created_at' , 'DESC');
        if($request->search != ''){
            $query->where('order_details.invoice_number' , 'LIKE' , '%'.$request->search.'%');
        }
        if($request->date != ''){
            $query->where('order_details.created_at', '>', $request->date);
        }
        if($request->filter != ''){
            $id_status = AppMasterStatusOrder::select('id')->where('status_code' , $request->filter)->where('status' ,'active')->first();
            $query->where('order_details.status' , $id_status->id);
        }
        $order = $query->paginate($request->per_page);
        
        if($order){
            $data['list'] = OrderVendorResource::collection($order);
            $data['current_page'] = $order->currentPage();
            $data['total'] = $order->lastPage();
            return $this->successResponse($data);
            //return $this->successResponse(OrderVendorResource::collection($order));
        }
        else{
            return $this->successResponse([]);
        }
    }

    public function validate_data_by_invoice($invoice) {
        $user = $this->user();
        $vendor = Vendor::where('user_id',$user->id)->first();
        $refund = OrderDetail::select('refunds.*')->join('refunds','refunds.order_details_id' ,'order_details.id')
                  ->where('order_details.invoice_number' , $invoice)
                  ->where('order_details.vendor_id' , $vendor->id)
                  ->where('order_details.status' , 6)
                  ->first();
        if($refund){
            return $refund;
        }
        return false;
    }

    public function refund_by_invoice(Request $request) {
        $user = $this->user();
        $vendor = Vendor::where('user_id',$user->id)->first();
        $order_detail_id = OrderDetail::where('invoice_number' , $request->invoice)->where('vendor_id' , $vendor->id)->whereIn('status' , [6, 7])->first();
        if(!$order_detail_id){
            return $this->errorResponse('Maaf, detail refund tidak tersedia' ,500);
        }
        $detail_refund = Refund::select('refunds.*','refund_status.status','refund_status.description as status_description')
            ->leftJoin('refund_status' ,'refunds.refund_status_id' , 'refund_status.id')
            ->with('logs')  
            ->with('bank_account')
            ->with('refund_confirmation')
            ->with('transaction.status_order')
            ->where('order_details_id' , $order_detail_id->id)->first(); 
        if(!$detail_refund){
            return $this->errorResponse('Maaf, detail refund tidak tersedia' ,500);
        }
        $detail_refund->tracking = $this->get_tracking($detail_refund->transaction->id);
        $detail_refund->tracker = ['pending'=> 'Proses penjual', 'delivery' => 'dikirim', 'arrived' => 'Pesanan Sampai'];
        return $this->successResponse($detail_refund);
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

    public function refund_seller_approve(Request $request) {
        
        $vendor = $this->vendor();
        if ($vendor == null) {
            return $this->errorResponse(self::ERROR_NOT_AUTHORIZED, self::ERROR_NOT_LOGIN_CODE);
        }
        $validation = Validator::make($request->all(), [
            'invoice' => 'required',
        ]);
        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),'201');
        }
        $refund_id = $this->validate_data_by_invoice($request->invoice);
        if ($refund_id){
            if($refund_id->refund_status_id == 9){
                $refund = Refunds::update_status($refund_id->id, 4 , $request->reason);
                if ($refund) {
                    return $this->successResponse(true);
                } else {
                    return $this->errorResponse(self::ERROR_WHEN_SAVE_DATA, self::ERROR_WHEN_SAVE_DATA_CODE);
                }
            }
        } else {
            return $this->errorResponse(self::ERROR_NOT_AUTHORIZED, self::ERROR_WHEN_DATA_NOT_FOUND_CODE);
        }
        return $this->errorResponse(self::ERROR_WHEN_SAVE_DATA, self::ERROR_WHEN_SAVE_DATA_CODE);

    }

    public function refund_seller_process(Request $request) {
        $vendor = $this->vendor();
        if ($vendor == null) {
            return $this->errorResponse(self::ERROR_NOT_AUTHORIZED, self::ERROR_NOT_LOGIN_CODE);
        }
        $validation = Validator::make($request->all(), [
            'invoice' => 'required',
        ]);
        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),'201');
        }
        $refund_id = $this->validate_data_by_invoice($request->invoice);
        if ($refund_id){
            if($refund_id->refund_status_id == 1){
                $refund = Refunds::update_status($refund_id->id, 2 , $request->reason);
                if ($refund) {
                    return $this->successResponse(true);
                } else {
                    return $this->errorResponse(self::ERROR_WHEN_SAVE_DATA, self::ERROR_WHEN_SAVE_DATA_CODE);
                }
            }
        } else {
            return $this->errorResponse(self::ERROR_NOT_AUTHORIZED, self::ERROR_WHEN_DATA_NOT_FOUND_CODE);
        }
        return $this->errorResponse(self::ERROR_WHEN_SAVE_DATA, self::ERROR_WHEN_SAVE_DATA_CODE);
    }

    public function refund_seller_cancel(Request $request) {
        $vendor = $this->vendor();

        if ($vendor == null) {
            return $this->errorResponse(self::ERROR_NOT_AUTHORIZED, self::ERROR_NOT_LOGIN_CODE);
        }

        $validation = Validator::make($request->all(), [
            'invoice' => 'required',
            'reason' => 'required',
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),'201');
        }

        $refund_id = $this->validate_data_by_invoice($request->invoice);
        if ($refund_id){
            $refund = Refunds::update_status($refund_id->id, 8 , $request->reason);
            if ($refund) {
                return $this->successResponse(true);
            } else {
                return $this->errorResponse(self::ERROR_WHEN_SAVE_DATA, self::ERROR_WHEN_SAVE_DATA_CODE);
            }
        } else {
            return $this->errorResponse(self::ERROR_NOT_AUTHORIZED, self::ERROR_WHEN_DATA_NOT_FOUND_CODE);
        }
        return $this->errorResponse(self::ERROR_WHEN_SAVE_DATA, self::ERROR_WHEN_SAVE_DATA_CODE);
    }
    
}
