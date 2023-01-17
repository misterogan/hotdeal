<?php

namespace App\Http\Resources;

use App\OrderDetailLog;
use App\ProductDetail;
use App\Refund;
use App\ViewProduct;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailCancelProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'invoice' => $this->invoice_number,
            'status' => $this->status_order,
            'vendor' => $this->vendor,
            'last_status' => $this->order_detail_logs($this->status , $this->id),
            'product' => $this->productswithdetail,
            'shipping' => $this->shipping,
            'payment' => $this->payment,
            'refund' => $this->refund_data($this->id),
            'logs' =>$this->order_logs,
            'total_payment' => $this->invoice_total_payment,
            'total_discount' => $this->invoice_total_discount,
            'created_at' => $this->created_at->format('Y/m/d')
        ];
    }

    function get_product($product_detail_id){
        $product_details = ViewProduct::select('slug','name','product_id','sku')->with('image')->where('product_detail_id',$product_detail_id)->first();
        return $product_details;

    }

    private function order_detail_logs($status , $id){
        $last_status = OrderDetailLog::where('status' , $status)->where('order_details_id' , $id)->first();
        return $last_status ? $last_status : null;
    }

    private function refund_data($id){
        $data = Refund::where('order_details_id' , $id)->with('bank_account')->with('logs')->first();
        return $data ? $data : ['bank_account' => null,'refund_type' => null];
    }
}
