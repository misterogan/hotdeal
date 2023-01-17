<?php

namespace App\Http\Controllers\Api;

use App\Cart;
use App\Helpers\Logistics;
use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Resources\CheckoutResource;
use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductShortResource;
use App\ProductDetail;
use App\ShipmentService;
use App\User;
use App\UserAddress;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Api
{
    /**
     * @OA\Get(
     * path="/api/checkout/step/1",
     * summary="Checkout",
     * description="get checkout",
     * operationId="get checkout",
     * tags={"Checkout"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(
     *        )
     *     )
     * )
     */

    public function get_in_checkout(Request $request){

        $user = $this->user();

        $orders = Cart::with('products')
            ->where('user_id',$user->id)
            ->where('in_checkout',true)->get();

        $data = [
            'orders'=>CheckoutResource::collection($orders),
//            'orders'=>$orders,
        ];
        return $this->successResponse($data);

    }

    /**
     * @OA\Get(
     * path="/api/checkout/summary",
     * summary="Checkout",
     * description="Checkout Summary",
     * operationId="summary",
     * tags={"Checkout"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(
     *        )
     *     )
     * )
     */

    public function summary(Request $request){
        $user = $this->user();
        $cart = Cart::select('products.name','products.weight' ,'products.dimension' ,'products.id','cart.product_details_id','products.vendor_id' ,'cart.quantity' ,'view_products.face_price')
            ->join('view_products' ,'view_products.product_detail_id' , 'cart.product_details_id')
            ->join('products' ,'view_products.product_id' , 'products.id' )
            ->where('user_id',$user->id)
            ->where('cart.status', 'active')
            ->where('products.status', 'active')
            ->where('in_checkout',true)->get();
        $orders = [];
        $total_payment = 0;
        if($cart){
            foreach($cart as $key=>$val){
                $orders[$val->vendor_id][] = $val;
                $total_payment += ($val->face_price * $val->quantity);
            }
        }
        $response = [];
        $product = [];
        $total = 0;
        foreach($orders as $key=>$val){
            $total_payment_per_merchant = 0;
            $weight = 0;
            foreach($val as $key2=>$val2){
                $cart = Cart::leftJoin('product_details' ,'product_details.id' ,'cart.product_details_id')
                        ->leftJoin('products','product_details.product_id', 'products.id')
                        ->where('cart.user_id',$user->id)
                        ->where('cart.status' ,'active')
                        ->where('cart.product_details_id' , $val2->product_details_id)
                        ->get();
                //echo json_encode($val2); exit;
                $weight += (int)($cart[0]->weight) * (int)($cart[0]->quantity);
                //$dimensions ='10x10x10';
               
                $product[] = CartResource::collection($cart);
                $total++;
                $total_payment_per_merchant += ($val2->face_price * $val2->quantity);
            }
            $dimensions = Logistics::set_package_shipper($val);
            $response[] = [
                'total_payment' => $total_payment_per_merchant,
                'vendor' =>  Vendor::where('id' , $key)->with('province')->with('city')->first(),
                'product_detail' =>  $product,
                //'logistics' => $this->payload_logistic($key , $weight , $total_payment , $dimensions), //Logistics::pricing($this->payload_logistic($key , $weight , $total_payment , $dimensions))
                'logistic' => $key == 34 ? Logistics::pricing_by_rate_instant($this->payload_logistic($key , $weight , $total_payment_per_merchant , $dimensions)) : Logistics::pricing($this->payload_logistic($key , $weight , $total_payment_per_merchant , $dimensions))
            ];
            $product = [];
        }
        $data = [
            'payment' => $total_payment,
            'summary' => $response,
            'total' => $total
        ];
        $response['payment'] = Utils::currency_convert($total_payment);
        return $this->successResponse($data);
    }

    public function payload_logistic($vendor_id , $weight , $total_payment , $dimensions ){
        
        $user = UserAddress::where('user_id' , Auth::user()->id)->where('is_primary_address' , true)->first();
        $consignee = Vendor::select('vendors.*' , 'users.phone', 'areas.api_id as areas_api' , 'suburbs.api_id as suburbs_api')
        ->join('users' , 'users.id' , 'vendors.user_id')
        ->leftJoin('areas' , 'vendors.area_id','areas.id')
        ->leftJoin('suburbs' , 'vendors.suburb_id','suburbs.id')
        ->where('vendors.id' , $vendor_id)->first();

       // print_r($consignee);

        $consigner = UserAddress::select('user_addresses.recipient_name','user_addresses.address','user_addresses.regency_id','user_addresses.area_id','user_addresses.lng','user_addresses.lat','user_addresses.phone_number','provinces.name as province_name',
        'cities.name as city_name','suburbs.name as suburbs_name','areas.name as area_name' , 'areas.api_id as areas_api' ,'suburbs.api_id as suburbs_api')
            ->leftJoin('provinces' , 'user_addresses.province_id','provinces.api_id')
            ->leftJoin('cities' , 'user_addresses.city_id','cities.id')
            ->leftJoin('suburbs' , 'user_addresses.regency_id','suburbs.id')
            ->leftJoin('areas' , 'user_addresses.area_id','areas.id')
            ->where('user_id' , $user->user_id)
            ->where('is_primary_address' , true)
            ->first();
        if(!$consignee || !$user){
            return [];
        }
        //$dimension = explode('x' , $dimensions);

        $consignee_lat = '';
        $consignee_lng = '';

        if($consignee->active_sameday){
           $consignee_lat = $consignee->lat;
           $consignee_lng = $consignee->lng;
        }

        return array(
            "destination" => array(
                "area_id"=> (int)($consigner->areas_api),
                "lat"=> $consigner->lat,
                "lng"=>$consigner->lng,
                "suburb_id"=> (int)($consigner->suburbs_api)
            ),
            "origin" => array(
                "area_id"=>(int)($consignee->area_id),
                "lat"=>$consignee_lat,
                "lng"=>$consignee_lng,
                "suburb_id"=> (int)($consignee->suburb_id)
            ),
            "for_order"             => true,
            "cod"                   => false,
            "height"                => $dimensions['height'],
            "length"                => $dimensions['length'],
            "width"                 => $dimensions['width'],
            "weight"                => $dimensions['weight'],
            "item_value"            => $total_payment,
            "limit"                 => 30,
            "page"                  => 1,
            "sort_by"               => array('final_price')
        );
    }

}
