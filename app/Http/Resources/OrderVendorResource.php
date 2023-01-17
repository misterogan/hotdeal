<?php

namespace App\Http\Resources;
use App\Helpers\Utils;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderVendorResource extends JsonResource
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
            'invoice_number' => $this->invoice_number,
            'invoice_total_payment' => $this->invoice_total_payment,
            'invoice_total_discount' => $this->invoice_total_discount,
            'transaction_number' => $this->order->transaction_number,
            'created_at' => date( 'Y-m-d H:i:s' , strtotime($this->created_at )),
            'total_payment ' => Utils::currency_convert($this->order->total_payment),
            'total_discount' => Utils::currency_convert($this->order->total_discount),
            'image' => $this->format_image($this->product->galleries),
            'product_name'=> $this->product->product_detail->product->name,
            'products' =>  OrderDetailProductResource::collection($this->products),
            'sku' => $this->product->product_detail->product->name,
            'order' => $this->order,
            'order_payment' => $this->order->payment,
            'shipping' => $this->shipping,
            'status_order' => $this->status_order,
            'refund' => $this->refund,
            'order_logs' => $this->logs_order($this->order_logs),
            'estimasi' => $this->estimasi
        ];
    }

    private function format_value($data){
        return [
            'stock' => $data->stock,
            'price' => $data->price,
            'image' => $data->product_galleries_id
        ];
    }

    function logs_order($data){
        $response = [];
        foreach($data as $item){
            $response[$item->master_status->status_code] = $item->master_status->description;
        }
        return $response;
    }

    private function format_image($data){
        $items = [];
        if(count($data) > 0){
            foreach($data as $item){
                $items[] = [
                    'detail' => $item->product_detail_id,
                    'type' => $item->type == 1 ? 'image' : 'video',
                    'url' => $item->link,
                ];
            }
        }
        return $items;
    }
}
