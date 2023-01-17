<?php

namespace App\Http\Controllers\Admin;

use App\Area;
use App\Cities;
use App\Helpers\Logistics;
use App\Helpers\Notify;
use App\Helpers\Utils;
use App\Http\Controllers\Api\Api;
use App\Http\Controllers\Controller;
use App\OrderDetail;
use App\OrderDetailShipping;
use App\Province;
use App\Shipment;
use App\ShipmentService;
use App\Suburbs;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AutoController extends Api
{
    public function insert_location(Request $request){

        if($request->type == 'province'){
            $url = 'https://merchant-api.shipper.id/v3/location/country/228/provinces?page=1&limit=1000000';
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "x-api-key: UlBd5QtDldi8ISuOmx5bNQ6SA5SLVBRS",
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            $response = json_decode($response,true);
            //echo json_encode($response);
            foreach($response['data'] as $key=>$val){
              //  print_r($val);
                Province::updateOrCreate(
                    [
                        'api_id' => $val['id']
                    ],
                    [
                        'api_id' => $val['id'],
                        'name' => $val['name'],
                        'lat' => $val['lat'],
                        'lng' => $val['lng'],
                        'source' => 'shipper',
                        'created_at' => Utils::now(),
                        'updated_at' => Utils::now()

                    ]
                );
            }
        }

        if($request->type == 'city'){
            $province = Province::get();
            foreach($province as $item){
                $url = 'https://merchant-api.shipper.id/v3/location/province/'.$item->api_id.'/cities?page=1&limit=1000000';
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        "x-api-key: UlBd5QtDldi8ISuOmx5bNQ6SA5SLVBRS",
                    ),
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);
                $response = json_decode($response,true);
                foreach($response['data'] as $key=>$val){
                    Cities::updateOrCreate(
                        [
                            'api_id' => $val['id'],
                            'province_id' => $item->id
                        ],
                        [
                            'api_id' => $val['id'],
                            'name' => $val['name'],
                            'province_id' => $item->id,
                            'lat' => $val['lat'],
                            'lng' => $val['lng'],
                            'created_at' => Utils::now(),
                            'updated_at' => Utils::now()
    
                        ]
                    );
                }
            }
            
        }

        if($request->type == 'suburbs'){
            $province = Cities::get();
            foreach($province as $item){
                $url = 'https://merchant-api.shipper.id/v3/location/city/'.$item->api_id.'/suburbs?page=1&limit=1000000';
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        "x-api-key: UlBd5QtDldi8ISuOmx5bNQ6SA5SLVBRS",
                    ),
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);
                $response = json_decode($response,true);
                foreach($response['data'] as $key=>$val){
                    Suburbs::updateOrCreate(
                        [
                            'api_id' => $val['id'],
                            'city_id' => $item->id
                        ],
                        [
                            'api_id' => $val['id'],
                            'name' => $val['name'],
                            'city_id' => $item->id,
                            'lat' => $val['lat'],
                            'lng' => $val['lng'],
                            'created_at' => Utils::now(),
                            'updated_at' => Utils::now()
    
                        ]
                    );
                }
            }
            
        }

        if($request->type == 'area'){
            $start = $request->start;
            $suburs = Suburbs::where('id','>',$start )->limit(300)->get();
            //print_r($suburs);
            foreach($suburs as $item){
                $url = 'https://merchant-api.shipper.id/v3/location/suburb/'.$item->api_id.'/areas?page=1&limit=1000000';
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        "x-api-key: UlBd5QtDldi8ISuOmx5bNQ6SA5SLVBRS",
                    ),
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);
                $response = json_decode($response,true);
                foreach($response['data'] as $key=>$val){
                    Area::updateOrCreate(
                        [
                            'api_id' => $val['id'],
                            'suburb_id' => $item->id
                        ],
                        [
                            'api_id' => $val['id'],
                            'name' => $val['name'],
                            'suburb_id' => $item->id,
                            'lat' => $val['lat'],
                            'lng' => $val['lng'],
                            'created_at' => Utils::now(),
                            'updated_at' => Utils::now()
    
                        ]
                    );
                }
            }
            
        }

    }

    public function info(Request $request){
        phpinfo();
    }
    public function uat(Request $request){
        $data = '{
            "data": {
                "order_activation": {
                    "order_id": [
                        "21BN8Y26N2ZQ4"
                    ],
                    "pickup_time": "2021-11-25T17:58:00+07:00"
                }
            }
        }';
        $url = 'https://merchant-api.shipper.id/v3/location?adm_level=5&keyword=jakarta';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST,           1 );
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array(
                "x-api-key: UlBd5QtDldi8ISuOmx5bNQ6SA5SLVBRS",
                "Content-Type: application/json"
            ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);
        curl_close($ch);
        echo "<pre>";
        print_r($response);
    }
    public function shipper_callback(Request $request){
        $shipment = 'shipper';
        $callback =  Logistics::callback(json_encode($request->all()),$shipment);
        if($callback){
            $response['message'] = "success";
            return $this->successResponse($response);
        }else{
            return $this->errorResponse(static::CALLBACK_WRONG,static::STATUS_CALLBACK_CANCELED);
        }
    }
    public function test_invoice(){
        echo Utils::invoice_number();
    }

    public function test_notification(){
        $title = 'test title';
        $body = 'test body';
        $url = 'test url';
        $image = 'test image';
        $send = 'uid';
        $topic = 'test topic';
        $user_id = '1';
        Notify::send($title,$body,$url,$image,$send,$topic,$user_id);
    }

    public function create_order_shipper(){
          $id = 231;
          $logistic = Logistics::createOrder($id);
          if($logistic){
              echo "berhasil melakukan pickup";
          }else{
              echo "gagal melakukan pickup";
          }
    }

    public function cancel_order_shipper(){
        $order_detail_shipping = OrderDetailShipping::where('id',135)->first();
        $data = json_decode($order_detail_shipping->logistic_detail, true);
        $payload = [
            "reason" => "Stock barang habis",
        ];

        $url = 'https://merchant-api-sandbox.shipper.id/v3/order/2155YY54QW2PM';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST,           1 );
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array(
                "x-api-key: aDHFyFwtKjcJmSxiaGsyGKXwvlEN0gLvse2moy8ZgTMHce8JGfMunHgsOSqlFFsL",
                "Content-Type: application/json"
            ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        $response = curl_exec($ch);
        curl_close($ch);
        print_r($response);
    }


    public function pricing(){

        $payload = '{
                      "cod": false,
                      "destination": {
                        "area_id": 4711,
                        "lat": "-6.2195686",
                        "lng": "106.8325872",
                        "suburb_id": 482
                      },
                      "for_order": true,
                      "height": 10,
                      "item_value": 40000,
                      "length": 10,
                      "limit": 30,
                      "origin": {
                        "area_id": 4711,
                        "lat": "-6.2195686",
                        "lng": "106.8325872",
                        "suburb_id": 482
                      },
                      "page": 1,
                      "sort_by": [
                        "final_price"
                      ],
                      "weight": 0.5,
                      "width": 10
                    }';
        $url = 'https://merchant-api.shipper.id/v3/pricing/domestic';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST,           1 );
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array(
                "X-API-Key: UlBd5QtDldi8ISuOmx5bNQ6SA5SLVBRS",
                "Content-Type: application/json"
            ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response,true);
        echo "<pre>";
        print_r($response['data']['pricings']);
    }

    public function late_delivery(Request $request){

//        foreach (){
//
//        }
//        $invoice_number = 'INV/211220/HDID/0001';
//        $order_detail = OrderDetail::where('invoice_number',$invoice_number)->first();
//        $order_detail_shipping = OrderDetailShipping::where('order_details_id',$order_detail->id)->first();
//        $max_date_estimate = $order_detail_shipping->max_estimate_arrived;
//        echo $order_detail_shipping->created_at->addDays($max_date_estimate);

    }
}
