<?php
namespace App\Http\Resources;
use App\Helpers\Utils;
use App\MasterStatusOrder;
use App\ProductDetail;
use App\PromotionDiscountProduct;
use App\PromotionFreeShipment;
use App\Review;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'transaction_number'=>$this->transaction_number,
            'total_payment'=>$this->total_payment,
            'total_discount'=>$this->total_discount,
            'status'=> $this->get_status($this->status),
            'order_date'=>date("d/m/Y", strtotime($this->created_at)),
            'order_details'=> OrderDetailResource::collection($this->order_detail),
            'order_payments'=>  new OrderPaymentResource($this->order_payments),
            'order_shipping'=>  $this->detail->shipping,
        ];

    }

    private function format_value($data){
        return [
            'stock' => $data->stock,
            'price' => $data->price,
            'image' => $data->product_galleries_id
        ];
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

    private function get_shipping($product_id){
        $free_shipping  = PromotionFreeShipment::select('promo_from','minimum_payment','shipping_fee_discount','start_date','end_date')->where('product_id',$product_id)->where('status','active')->first();
        $date_now = date('Y-m-d H:i:s');
        if($free_shipping &&  $date_now >= $free_shipping->start_date && $date_now <= $free_shipping->end_date){
            return true;
        }else{
            return false;
        }
    }

    private function get_review($product_id){

        $review = Review::select('user_id','rating','review')->where('product_id',$product_id)->get();
        $review_count = Review::where('product_id',$product_id)->get();
        $review_count = count($review_count);
        $result = 0;
        $ratings_count = 0;
        $user_count = 0;
        foreach ($review as $key=> $val){
            $ratings_count= $ratings_count + $val->rating;
            $user_count = $user_count + 1;
        }
        if($review_count > 0){
            $res =  $ratings_count / $review_count ;
        }else{
            $res = 0;
        }

        return $res;
    }

    function get_status($status){
        $status = MasterStatusOrder::select('status_code' , 'description')->where('id',$status)->first();
        return $status;

    }



}
