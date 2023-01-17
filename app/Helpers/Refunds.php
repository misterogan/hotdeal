<?php
namespace App\Helpers;

use App\MasterStatusOrder;
use App\OrderDetail;
use App\OrderDetailLog;
use App\Refund;
use App\RefundLogs;
use App\Vendor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Order;
use App\RefundReturnConfirmation;
use Illuminate\Support\Facades\Log;

class Refunds {

    static function update_status($refund_id, $status_code, $description = '', $notify=true) {
        $refund = Refund::where('id', $refund_id)->first();
        $receipt_number = RefundReturnConfirmation::where('refund_id', $refund->id)->value('receipt_number');
        $invoice = $refund->detail->invoice_number;
        $refund->refund_status_id = $status_code;
        if ($refund->save()) {
            RefundLogs::create([
                'refund_status_id' => $status_code,
                'refund_id' => $refund_id,
                'description' => $description,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            if($notify){
                // Notify::send('Refund', $description, '/', '', 'uid', 'Refund', $refund->user_id);
                if ($status_code == 3){
                    Notify::send(
                        Notify::$REFUND_APPROVE_TITLE, 
                        'Paket ' . $receipt_number . ' dari pengajuan refund ' . $invoice . ' telah diterima dan dikonfirmasi', 
                        '/', 
                        '', 
                        'uid', 
                        'Refund', 
                        $refund->user_id
                    );
                    Notify::send(
                        Notify::$REFUND_APPROVE_TITLE, 
                        'Paket ' . $receipt_number . ' dari pengajuan refund ' . $invoice . ' telah diterima dan dikonfirmasi', 
                        '/', 
                        '', 
                        'uid', 
                        'Refund', 
                        '0'
                    );
                } if ($status_code == 5){
                    Notify::send(
                        'Pengembalian dana diproses', 
                        'Pengembalian dana untuk pengajuan ' . $invoice . ' telah terkonfirmasi dan akan diproses', 
                        '/', 
                        '', 
                        'uid', 
                        'Refund', 
                        $refund->detail->vendor->user_id
                    );
                    Notify::send(
                        'Pengembalian dana diproses', 
                        'Pengembalian dana untuk pengajuan ' . $invoice . ' telah terkonfirmasi dan akan diproses', 
                        '/', 
                        '', 
                        'uid', 
                        'Refund', 
                        $refund->user_id
                    );
                    Notify::send(
                        'Pengembalian dana diproses', 
                        'Pengembalian dana untuk pengajuan ' . $invoice . ' telah terkonfirmasi dan akan diproses', 
                        '/', 
                        '', 
                        'uid', 
                        'Refund', 
                        '0'
                    );
                }
                
            }
            return true;
        }else {
            return false;
        }
    }

    public static function save_detail_log($refund_id,$refund_status_id,$description){
        $refund_log = RefundLogs::create([
            'refund_id'=>$refund_id,
            'refund_status_id'=>$refund_status_id,
            'description'=>$description,
            'created_at'=>date('Y-m-d H:i:s'),
        ]);
        if($refund_log){
            return true;
        }else{
            return false;
        }
    }

}
