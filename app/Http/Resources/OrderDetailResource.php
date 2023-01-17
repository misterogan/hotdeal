<?php

namespace App\Http\Resources;

use App\Helpers\Logistics;
use App\Order;
use App\OrderDetail;
use App\OrderDetailShippingTracker;
use App\Product;
use App\Review;
use App\ReviewGallery;
use App\Vendor;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
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
            'status_order' => $this->status_order,
            'invoice_number'=>$this->invoice_number,
            'total_payment'=>$this->total_payment,
            'total_discount'=>$this->calculate_total_discount($this->order_id,$this->total_discount),
            'point'=>$this->point,
            'invoice_total_payment'=>$this->invoice_total_payment,
            'invoice_total_discount'=>$this->invoice_total_discount,
            'created_at'=>date('Y-m-d' , strtotime($this->created_at )),
            'vendor_id'=> $this->get_vendor($this->vendor_id),
            'order_detail_product'=> OrderDetailProductResource::collection($this->order_products),
            'order_logs'=> $this->logs_order($this->order_logs),
            'shipping'=> $this->shipping,
            'tracking' => $this->get_tracking($this->id),
            'review' => $this->get_reviews($this->id),
            'interval' => $this->get_interval($this->id),
            'estimasi' => $this->get_estimasi($this->invoice_number, $this->estimasi)
        ];
    }
    function get_interval($id){
        $order_details = OrderDetail::where('id', $id)->first();
        $now = new DateTime("now");
        $product_date = new DateTime($order_details->created_at);
        $interval = $now->diff($product_date);

        return $interval->days;
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
    function calculate_total_discount($order_id , $amount_discount){
        $total_transaction = OrderDetail::where('order_id' , $order_id)->count();
        if($total_transaction > 0){
            return floor($amount_discount / $total_transaction);
        }
        return $amount_discount;
        
    }
    function get_vendor($vendor_id){
        $vendors = Vendor::
            select('vendors.image','users.phone' ,'vendors.address','vendors.id as vendor_id','vendors.name as vendor_name','vendors.pic as pic','vendors.user_id','provinces.name as province','cities.name as city')
            ->leftjoin('users', 'users.id', '=', 'vendors.user_id')
            ->leftjoin('provinces', 'vendors.province_id', '=', 'provinces.id')
            ->leftjoin('cities', 'vendors.city_id', '=', 'cities.api_id')
            ->where('vendors.id',$vendor_id)->first();
        return $vendors;

    }

    function logs_order($data){
        $response = [];
        foreach($data as $item){
            $response[$item->master_status->status_code] = $item->master_status->description;
        }
        return $response;
    }

    function get_reviews($id){
        $reviews = Review::where('order_details_id', $id)->with('review_gallery')->with('image')->with('video')->where('status', 'active')->get();
        // $data['review'] = $reviews;
        return $reviews;
    }

    function get_estimasi($inv, $est){
        $today = OrderDetail::where('invoice_number', $inv)->pluck('updated_at')->first();
        
        $estimasi = $est;
        $estimasi = $estimasi == null ? '3' : $estimasi;
        if(strlen($estimasi) > 2){
            $splitEst = explode(' - ', $estimasi);
            $estimasi = $today->addDay($splitEst[0])->format('d').' - '.$today->addDay($splitEst[1])->format('d M');
        } else {
            $estimasi = $today->addDay($estimasi)->format('d M');
        }
        return $estimasi;
    }
}
