<?php
namespace App\Helpers;

use App\Jobs\ProcessDeliveredOrder;
use App\Jobs\TransactionEmailNotification;
use App\Order;
use App\OrderDetail;
use App\OrderDetailShipping;
use App\MediaLog;
use App\OrderDetailLog;
use App\OrderDetailShippingLogs;
use App\OrderDetailShippingTracker;
use App\Shipment_log;
use App\ShipmentLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Constraint\IsFalse;

class Logistics {

    static $SHIPPER_URL_SANDBOX = "https://merchant-api-sandbox.shipper.id/v3";
    static $API_KEY_SANBOX = "aDHFyFwtKjcJmSxiaGsyGKXwvlEN0gLvse2moy8ZgTMHce8JGfMunHgsOSqlFFsL";
    //production
    static $SHIPPER_URL_PRODUCTION = "https://merchant-api.shipper.id/v3";
    static $API_KEY_PRODUCTION = "UlBd5QtDldi8ISuOmx5bNQ6SA5SLVBRS";
    //static $SHIPPER_URL_PRODUCTION = "https://merchant-api-sandbox.shipper.id/v3";
    //static $API_KEY_PRODUCTION = "aDHFyFwtKjcJmSxiaGsyGKXwvlEN0gLvse2moy8ZgTMHce8JGfMunHgsOSqlFFsL";

    static $LOGISTIC_STATUS = 
        [
        'SHIPPER' => [
            '1000' => ['name' => 'Paket sedang dipersiapkan' , 'description' => 'Paket sedang dipersiapkan'],
            '1010' => ['name' => 'Tunggu Penjemputan' , 'description' => 'Menunggu Penjemputan oleh {driver_name}'],
            '1020' => ['name' => 'Sedang Dijemput	Paket','description' => 'sedang dijemput driver {driver_name}'],
            '1030' => ['name' => 'Proses Penjemputan', 'description' =>	'Paket dalam proses penjemputan oleh driver {driver_name}'],
            '1040' => ['name' => 'Perjalanan ke HUB',	'description' => 'Paket dalam perjalanan menuju Hub Shipper {hub_location}'],
            '1050' => ['name' => 'Sampai di HUB', 'description' => 'Paket diterima hub {hub_location}'],
            '1060' => ['name' => 'Sortir Barang',	'description' => 'Paket Menuju Gudang Sorting {warehouse_location}'],
            '1070' => ['name' => 'Dikirim ke {3pl_name}', 'description' => 'Paket Dikirim ke {3pl_name}'],
            '1080' => ['name' => 'Diterima oleh {3pl_name}', 'description' =>	'Paket Diterima oleh {3pl_name}'],
            '1090' => ['name' => 'Paket Terkirim', 'description' => 'Paket terkirim dan diterima oleh {recipient_name}'],
            '1100' =>	['name' => 'Dikembalikan ke pengirim {reason}','Dikembalikan ke pengirim {reason}'],
            '999'	=>  ['name' => 'Cancelled by {actor_name}' ,'description'=> 'Dibatalkan oleh {actor_name}'],
        ],
    ];

    public static function pricing($payload = []){
        $response = Http::withHeaders([
            'X-API-Key' =>self::environment_key(),
            'Content-Type' => 'application/json',
        ])->post( self::environment_url().'/pricing/domestic' , $payload);
        $result = json_decode($response->body() , true);
        MediaLog::insert(['log' => $response->body()]);
        if(array_key_exists('data' , $result)){
            if(array_key_exists('pricings' , $result['data'])){
                return self::process_pricings( $result['data']['pricings']);
                //return $result['data']['pricings'];
            }
        }
        
        return [];
    }

    public static function pricing_by_rate_instant($payload = []){
        $response = Http::withHeaders([
            'X-API-Key' =>self::environment_key(),
            'Content-Type' => 'application/json',
        ])->post( self::environment_url().'/pricing/domestic/instant' , $payload);
        $result = json_decode($response->body() , true);
        MediaLog::insert(['log' => $response->body()]);
        if(array_key_exists('data' , $result)){
            if(array_key_exists('pricings' , $result['data'])){
                return self::process_pricings( $result['data']['pricings']);
                //return $result['data']['pricings'];
            }
        }
        
        return [];
    }

    public static function process_pricings($logistic_pricings){
        $data = [];
        $data['direct'] = [];
        $data['nondirect'] = [];
        if(count($logistic_pricings) > 0){
            foreach($logistic_pricings as $item){
                if($item['rate']['is_hubless'] == false){
                    if($item['logistic']['id'] == 26 || $item['logistic']['id'] == 38 || $item['rate']['id'] == 566 || $item['rate']['id'] == 560){
                        continue;
                    }
                    if(str_replace(' ','_', $item['rate']['type']) == 'Instant' || str_replace(' ','_', $item['rate']['type']) == 'Same_Day'){
                        $data['direct'][] = $item;
                    }else{
                        $data['nondirect'][] = $item;
                    }
                }
            }
        }
        return $data;
    }

    
    public static function createOrder($id_order_detail_shipping){

        $order_detail_shipping = OrderDetailShipping::where('id',$id_order_detail_shipping)->first();
        $order_details_id = $order_detail_shipping->order_details_id;
        $order_details = OrderDetail::where('id',$order_details_id)->first();
        $invoice_number = $order_details->invoice_number;
        
        $data = json_decode($order_detail_shipping->logistic_detail, true);

        $payload = [
            'consignee' => [
                "name" => $data['consignee']['name'],
                "phone_number"=> $data['consignee']['phone_number']
            ],
            'consigner' =>[
                "name" => $data['consigner']['name'],
                "phone_number"=> $data['consigner']['phone_number']
            ],
            'courier' => [
                "cod"=> false,
                "rate_id"=>$data['courier']['rate_id']['rate_id'],
                "use_insurance"=> false
            ],
            'coverage' => $data['coverage'],
            "destination"=>[
                "address"=> $data['destination']['address'],
                "area_id"=> $data['destination']['area_id'],
                "lat"=> $data['destination']['lat'],
                "lng"=> $data['destination']['lng']
            ],
            "external_id"=> $data['external_id']."14",
            "origin"=>[
                "address"=> $data['origin']['address'],
                "area_id"=> $data['origin']['area_id'],
                "lat"=> $data['origin']['lat'],
                "lng"=> $data['origin']['lng']
            ],
            "package"=> [
                "height"=> $data['package']['height'],
                "items" => $data['package']['items'],
                "length"=> $data['package']['length'],
                "package_type"=>$data['package']['package_type'],
                "price"=> floatval($data['package']['price']),
                "weight"=> $data['package']['weight'] / 1000,
                "width"=> $data['package']['width']
            ],
            "payment_type"=> "postpay"
        ];
        echo json_encode($payload); exit;
        $i = 0;
        foreach ($payload["package"]["items"] as $key => $item){
            $payload["package"]["items"][$i]["price"] =  floatval($item["price"]);
            $i++;
        }
        $url = env('SHIPPER_URL_SANDBOX').'/order';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST,           1 );
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array(
                "x-api-key: ".env('API_KEY_SANBOX'),
                "Content-Type: application/json"
            ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        $response = curl_exec($ch);
        curl_close($ch);
        $param = json_decode($response,true);

       if($param['metadata']['http_status_code'] == 201){

            $pickup = self::createPickup($param['data']['order_id']);
            if($pickup){

                $update = Orders::update_status_by_customer($invoice_number, $param['metadata']['http_status_code'], 2);
                return TRUE;
            }else{
                return FALSE;
            }
        }else{
           echo $param['metadata']['errors'][0]['message'];
           die();
       }


    }

    public static function checkOrder($payload = [], $order_id){
        $response = Http::withHeaders([
            'X-API-Key' => env('API_KEY_SANBOX'),
            'Content-Type' => 'application/json',
        ])->get(env('SHIPPER_URL_SANDBOX').'/order/'.$order_id, $payload);
        $result = json_decode($response->body() , true);
        if(array_key_exists('data' , $result)){
            if(array_key_exists('pricings' , $result['data'])){
                return $result['data']['pricings'];
            }
        }
        return [];
    }

    public static function deleteOrder($payload = [], $order_id){
        $response = Http::withHeaders([
            'X-API-Key' => env('API_KEY_SANBOX'),
            'Content-Type' => 'application/json',
        ])->delete(env('SHIPPER_URL_SANDBOX').'/order/'.$order_id, $payload);
        $result = json_decode($response->body() , true);
        if(array_key_exists('data' , $result)){
            if(array_key_exists('pricings' , $result['data'])){
                return $result['data']['pricings'];
            }
        }
        return [];
    }

    public static function createPickup($order_id){
        $max_time = "15:00:00";
        if(time() >= strtotime($max_time)){
            $pickup_time = Date('Y-m-d 15:00:00', strtotime('+1 days'));
        }else{
            $pickup_time = Date('Y-m-d 15:00:00');
        }
        $pickup_time = date("c", strtotime($pickup_time));
        $payload = [
            "data" => [
                "order_activation" =>
                    [
                        "order_id"=> ['21CY7XM37YWNE'],
                        "pickup_time"=> $pickup_time
                    ]
            ],
        ];

        $url = env('SHIPPER_URL_SANDBOX').'/pickup';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST,           1 );
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array(
                "x-api-key: ".env('API_KEY_SANBOX'),
                "Content-Type: application/json"
            ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        $response = curl_exec($ch);

        curl_close($ch);
        $param = json_decode($response,true);
        if($param['metadata']['http_status_code'] == 200){
            return true;
        }else{
            echo $param['metadata']['errors'][0]['message'];
            die();
        }

    }

    public static function deletePickup($payload = [], $order_id){
        $response = Http::withHeaders([
            'X-API-Key' => env('API_KEY_SANBOX'),
            'Content-Type' => 'application/json',
        ])->patch(env('SHIPPER_URL_SANDBOX').'/pickup/cancel', $payload);
        $result = json_decode($response->body() , true);
        if(array_key_exists('data' , $result)){
            if(array_key_exists('pricings' , $result['data'])){
                return $result['data']['pricings'];
            }
        }
        return [];
    }

    public static function webhook($data){
        $response = Http::withHeaders([
            'X-API-Key' => env('API_KEY_SANBOX'),
            'Content-Type: application/json',
        ])->patch(env('SHIPPER_URL_SANDBOX').'/pickup/cancel', $data);
        $result = json_decode($response->body() , true);
        if(array_key_exists('data' , $result)){
            if(array_key_exists('pricings' , $result['data'])){
                return $result['data']['pricings'];
            }
        }
        return [];
    }
    public static function callback($data,$shipment){
        $shipment_log = ShipmentLog::create([
           'payload'=>$data,
           'shipment_source'=>$shipment,
           'created_at'=>date('Y-m-d H:i:s')
        ]);
        if($shipment_log){
            return true;
        }else{
            return false;
        }
    }



    // FUNCTION FOR MANY LOGISTIC
    // CREATE ORDER
    public static function logistic_create_order($logistic , $id_order_detail){
        // check logistic available
        if($logistic == 'SHIPPER'){
            return self::shipper_logistic_create_order($id_order_detail);
        }
    }

    static function shipper_logistic_create_order($id_order_detail){
        //$get_shipping = OrderDetailShipping::where('order_details_id' , $id_order_detail)->first();
        
        $order_detail_shipping = OrderDetailShipping::where('order_details_id' , $id_order_detail)->first();
        $order_details_id = $order_detail_shipping->order_details_id;
        $order_details = OrderDetail::where('id',$order_details_id)->first();
        $invoice_number = $order_details->invoice_number;
        $data = json_decode($order_detail_shipping->logistic_detail, true);
        $payload = [
            'consignee' => [
                "name" => $data['consignee']['name'],
                "phone_number"=> $data['consignee']['phone_number']
            ],
            'consigner' =>[
                "name" => $data['consigner']['name'],
                "phone_number"=> $data['consigner']['phone_number']
            ],
            'courier' => [
                "cod"=> false,
                "rate_id"=>$data['courier']['rate_id']['rate_id'],
                "use_insurance"=> $data['courier']['rate_id']['detail']['must_use_insurance'] === true ? true : false
            ],
            'coverage' => $data['coverage'],
            "destination"=>[
                "address"=> preg_replace("/[^A-Za-z0-9\.,[:space:]]/"," ", preg_replace("/\r|\n/", "", $data['destination']['address'])),
                "area_id"=> $data['destination']['area_id'],
                "lat"=> $data['destination']['lat'],
                "lng"=> $data['destination']['lng']
            ],
            "external_id"=> $data['external_id'],
            "origin"=>[
                "address"=> preg_replace("/[^A-Za-z0-9\.,[:space:]]/"," ", preg_replace("/\r|\n/", "", $data['origin']['address'])),
                "area_id"=> $data['origin']['area_id'],
                "lat"=> $data['origin']['lat'],
                "lng"=> $data['origin']['lng']
            ],
            "package"=> [
                "height"=> $data['package']['height'],
                "items" => $data['package']['items'],
                "length"=> $data['package']['length'],
                "package_type"=>$data['package']['package_type'],
                "price"=> floatval($data['package']['price']),
                "weight"=> $data['package']['weight'] / 1000,
                "width"=> $data['package']['width']
            ],
            "payment_type"=> "postpay"
        ];
        $i = 0;
        foreach ($payload["package"]["items"] as $key => $item){
            $payload["package"]["items"][$i]["price"] =  floatval($item["price"]);
            $i++;
        }
        $response = Http::withHeaders([
            'X-API-Key' => self::environment_key(),
            'Content-Type' => 'application/json',
        ])->post( self::environment_url().'/order' , $payload);
        $param = json_decode($response->body() , true);

        static::createLogLogistic([
            'endpoint' => 'createorder',
            'order_detail_id' => $id_order_detail,
            'created_at' => Carbon::now(),
            'payload' => json_encode($payload),
            'response' => $response->body(),
        ]);

        if($param['metadata']['http_status_code'] == 201){
            // udpate Shipping
            OrderDetailShipping::where('order_details_id' , $id_order_detail)->update([
                'order_id' => $param['data']['order_id'],
                'logistic_name' => 'SHIPPER'
            ]);
            //$pickup = self::createPickupShipper($param['data']['order_id']);
            //if($pickup){
                //$update = Orders::update_status_by_customer($invoice_number, $param['metadata']['http_status_code'], 2);
            //}
        }else{
           echo $param['metadata']['errors'][0]['message'].'-->--'.json_encode($payload);
           die();
        }
        return true;
    }

    static function environment_key(){
        return config('app.env') == 'production' ? env('API_KEY_PRODUCTION') : env('API_KEY_SANBOX');
    }
    static function environment_url(){
        //env('APP_ENV') != 'staging' ? self::$SHIPPER_URL_SANDBOX.'/order' : self::$SHIPPER_URL_PRODUCTION.'/order'
        return config('app.env') == 'production' ? env('SHIPPER_URL_PRODUCTION') : env('SHIPPER_URL_SANDBOX');
    }

    // get order
    public static function get_order($logistic ,$order_id){
        if($logistic == 'SHIPPER'){
            self::shipperGetOrder($order_id);
        }
    }

    static function shipperGetOrder($order_id){
        $get_order = OrderDetailShipping::where('order_id' , $order_id)->first();
        
        $payload = [
            'order_id' => $order_id
        ];
        $response = Http::withHeaders([
            'X-API-Key' => self::environment_key(),
            'Content-Type' => 'application/json',
        ])->get(self::environment_url().'/order/'.$order_id, $payload);
        $response = json_decode($response->body() , true);
        
        if(self::shipper_response_api($response)){
            $get_order->label = $response['data']['label_check_sum'];
            $get_order->save();
            // OrderDetailShippingTracker::create([
            //     'tracker' => json_encode($response['data']),
            //     'order_detail_id' => $get_order->order_details_id,
            //     'created_at' => Carbon::now(),
            //     'updated_at' => Carbon::now()
            // ]);
        }
        $data = [
            'order_detail_id' => $get_order->order_details_id,
            'endpoint' => 'getorder',
            'created_at' => Carbon::now(),
            'payload' => json_encode($payload),
            'response' => json_encode($response)
        ];
        self::createLogGetOrder($data);
        return $get_order->label;

    }
    // END CREATE ORDER

    // CREATE PICKUP

    public static function createPickupLogistic($logistic , $order_id){
        if($logistic == 'SHIPPER'){
            return self::createPickupShipper($order_id);
        }
    }

    public static function createPickupShipper($order_id){
        $get_order = OrderDetailShipping::where('order_id' , $order_id)->first();

        if(!$get_order){
            return false;
        }
        $max_time = "15:00:00";
        if(time() >= strtotime($max_time)){
            $pickup_time = Date('Y-m-d 15:00:00', strtotime('+1 days'));
        }else{
            $pickup_time = Date('Y-m-d 15:00:00');
        }
        $pickup_time = date("c", strtotime($pickup_time));
        $payload = [
            "data" => [
                "order_activation" =>
                    [
                        "order_id"=> [$order_id],
                        "pickup_time"=> $pickup_time
                    ]
            ],
        ];
        $request = Http::withHeaders([
            'X-API-Key' => self::environment_key(),
            'Content-Type' => 'application/json',
        ])->post( self::environment_url().'/pickup' , $payload);
        $response = json_decode($request->body() , true);
        if(self::shipper_response_api($response)){
            if(array_key_exists('data' , $response)){
                if(array_key_exists('order_activations' , $response['data'])){
                    foreach($response['data']['order_activations'] as $key=>$val){
                        // udpate shipping
                        OrderDetailShipping::where('order_id' , $val['order_id'])->update([
                            'pickup_code' => $val['pickup_code'],
                            'is_activate' => $val['is_activate'],
                            'pickup_time' => date('Y-m-d H:i:s' , strtotime($val['pickup_time']))
                        ]);
                        // update status order detail
                        OrderDetail::where('id' , $get_order->order_details_id)->update([
                            'status' => 14
                        ]);
                        
                        OrderDetailLog::create([
                            'order_details_id' => $get_order->order_details_id,
                            'status' => 14,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            'created_by' => 'sistem',
                            'updated_by' => 'sistem',
                        ]);

                    }
                }else{
                    return false;
                }  
            }else{
                return false;
            }   
        }else{
            return false;
        } 

        $data = [
            'order_detail_id' => $get_order->order_details_id,
            'endpoint' => 'createpickup',
            'created_at' => Carbon::now(),
            'payload' => json_encode($payload),
            'response' => json_encode($response)
        ];
        if(!self::createLogLogisticPickup($data)){
        }
        return true;
    }

    // CREATE LOG
    // log create order
    static function createLogLogistic($data){
        OrderDetailShippingLogs::create(
            $data
        );
    }
    // log pickup
    static function createLogLogisticPickup($data){
        OrderDetailShippingLogs::create(
            $data
        );
        return true;
    }
    // log get order
    static function createLogGetOrder($data){
        OrderDetailShippingLogs::create(
            $data
        );
    }

    static function createLogWebhook($data){
        
        OrderDetailShippingLogs::create(
            $data
        );
    }

    // END LOG

    // WEBHOOK
    public static function webhookLogistic($logistic , $request){

        if($logistic == 'SHIPPER'){
           return self::webhookShipper($request);
        }
    }
    // Shipper webhook
    public static function webhookShipper($request){
        $order_id = $request->tracking_id;
        $get_order = OrderDetailShipping::where('order_id' , $order_id)->where('order_id' ,'!=', null)->first();
        if($get_order){
             // update status order detail
            if($request->external_status['code'] == 2000 || $request->external_status['code'] == 3000 ){
                Orders::update_status_from_webhook($get_order->order_details_id , 12);
                $invoice_number = OrderDetail::select('invoice_number')->where('id' , $get_order->order_details_id)->first();
                TransactionEmailNotification::dispatch($invoice_number->invoice_number , 'delivered');
                ProcessDeliveredOrder::dispatch($get_order->order_details_id)->delay(now()->addMinutes(30));
            }else{
                Orders::update_status_from_webhook($get_order->order_details_id , 4);
            }
            if(isset($request->awb) && $request->awb != null){
                $get_order->awb_number =  $request->awb;
            }
            OrderDetailShippingTracker::create([
                'order_detail_id' => $get_order->order_details_id,
                'code' => $request->external_status['code'],
                'tracker' => json_encode($request->all()),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            // update last tracking
            $get_order->shipment_code = $request->external_status['code'];
            $get_order->save();
        }
        $order_details_id =  $get_order->order_details_id;
        if(!$get_order){
            $order_details_id = 0;
        }
        $data = [
            'endpoint' => 'webhook',
            'order_detail_id' => $order_details_id,
            'created_at' => Carbon::now(),
            'payload' => '',
            'response' => json_encode($request->all()),
        ];
        self::createLogWebhook($data);
        return true;
    }

    //check response success
    static function shipper_response_api($response){
        if(array_key_exists('metadata' , $response)){
            if($response['metadata']['http_status_code'] == 200){
                return true;
            }
        }
        return false;
    }   

    public static function set_package_shipper($items){

        $product = [];
        $height = 0;
        $length = 0;
        $package_type = 2;
        $price  = 0;
        $weight = 0;
        $width = 0;
        $formula_status= false;
        $multiple_weight = 0;
        foreach($items as $item){
            if($item->dimension == null){
                $dimension = '10x10x10';
            }
            $dimension = explode('x' , strtolower($item->dimension) );

            $product[] = [
                'name' => $item->name,
                'price' => $item->face_price,
                'qty'=>$item->quantity
            ];
            
            $price += $item->face_price * $item->quantity;
            $multiple_weight += $item->quantity * (int)($dimension[0]) * (int)($dimension[1]) * (int)($dimension[2]);
            if($item->quantity > 1 || count($items) > 1){
                $formula_status= true;
                $weight += $item->weight;
            }else{
                $weight += $item->weight;
            }
            

        }
        if($formula_status){
            $weight = (int)($weight) > (int)($multiple_weight) ? (int)($weight) : (int)($multiple_weight);
            $weight = $weight / 6000 ;
            $width = round( pow((int)($multiple_weight),1/3) , 2);
            $length = round( pow((int)($multiple_weight),1/3) , 2);
            $height = round( pow((int)($multiple_weight),1/3), 2);
        }else{
            $weight = (int)($weight) / 1000;
            $width = (int)($dimension[1]);
            $length = (int)($dimension[0]);
            $height = (int)($dimension[2]);
        }

        return [
            'height' => $height,
            'width' => $width,
            'items' => $product,
            'length' => $length,
            'weight' => $weight,
            'price' => $price,
            'package_type' => $package_type
        ];

    }

}
