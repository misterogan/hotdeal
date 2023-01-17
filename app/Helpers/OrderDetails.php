<?php
namespace App\Helpers;

use App\Jobs\TransactionNotification;
use App\MasterStatusOrder;
use App\OrderDetail;
use App\OrderDetailLog;
use Carbon\Carbon;

class OrderDetails {
    
    protected $invoice;
    protected $status;
    
    public function __construct($invoice , $status){
        $this->invoice = $invoice;
        $this->status = $status;
    }

    public function cancelStatusAfterProcessedBySystem(){
        $status = MasterStatusOrder::where('status_code' , $this->status)->first();
        $invoice = OrderDetail::where('invoice_number' , $this->invoice->invoice_number)->first();    
        
        OrderDetail::where('id' , $invoice->id)->update([
            'status' => $status->id
        ]);
        TransactionNotification::dispatch($this->invoice->invoice_number , 'hotdeal_canceled');
        self::saveLogsDetail($invoice->id , $status->id , 'hotdeal' ,'hotdeal' , "Tidak memproses dalam 2x24 jam");
        return true;
    }

    public function cancelStatusAfterPaidBySystem(){
        $status = MasterStatusOrder::where('status_code' , $this->status)->first();
        $invoice = OrderDetail::where('invoice_number' , $this->invoice->invoice_number)->first();    
        OrderDetail::where('id' , $invoice->id)->update([
            'status' => $status->id
        ]);
        TransactionNotification::dispatch($this->invoice->invoice_number , 'hotdeal_canceled');
        self::saveLogsDetail($invoice->id , $status->id , 'hotdeal' ,'hotdeal' , "Tidak memproses dalam 1x24 jam");
        return true;
    }

    public  static function saveLogsDetail($o_d_id , $status_id , $updated_by , $created_by , $reason){
        OrderDetailLog::create([
            'order_details_id' => $o_d_id,
            'status' => $status_id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'created_by' => $created_by,
            'updated_by' => $updated_by,
            'description' => $reason
        ]);
    }
}
