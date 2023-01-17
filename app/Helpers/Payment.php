<?php
namespace App\Helpers;

use App\OrderPaymentLog;
use Illuminate\Support\Facades\Http;
use Xendit\Xendit;

class Payment {

    static $API_TOKEN_XENDIT_SANDBOX = 'xnd_development_V0VZMgCIbeUcvbjVeSGJVN9BMzk4KTUQivr6BbkHdMtEGkI3FsBUY21boE4YK';
    static $API_TOKEN_XENDIT = 'xnd_production_7pjOlNPLibNZ3GEtMtf06tOdex3RbmsxYHV3CORUoYMDBzTBf1Xmdhuj0oGqz2';
    static $HEADER_TOKEN = '8c050d0993d78f1d902cd511d609b25ee1b5fe5805de9d28347de887fcf8c4f0';
    static $HEADER_TOKEN_SANDBOX = 'e13c4f6f7868b0ab0e8ef33def7ca6e3ff80f34fdbbe78d7a1a9f4cdd28c4a91';
    static function api_key_environment(){    
        return config('app.env') == 'DEVELOPMENT' ? self::$API_TOKEN_XENDIT_SANDBOX : self::$API_TOKEN_XENDIT;
    }

    static function response($status ,$data , string $action=null){
        /** $status = boolean {true => success , false=failed} */
       // self::logs($data , $action);
        return [
            'status'=> $status,
            'data' => $data
        ];
    }

    static $currency = '';

    static function get_list_va($payload = []){
        Xendit::setApiKey(self::$API_TOKEN_XENDIT);
        $getVABanks = \Xendit\VirtualAccounts::getVABanks();
        print_r($getVABanks);
    }

    static function logs($response , $action , $order_id , $external_id ){
        OrderPaymentLog::create(
            [
                'response' => json_encode($response) , 
                'order_id' => $order_id , 
                'external_id' => $external_id ,
                'action' => $action , 
                'created_at' => Utils::now()
            ]
        );
    }
    /** START VA FUNCTIONAL */
    static function create_va($payload , $order_id){
        Xendit::setApiKey(self::api_key_environment());
        $create_va = \Xendit\VirtualAccounts::create($payload);
        if($create_va){
            self::logs($create_va , 'create_va' , $order_id , $create_va['external_id']);
            return self::response(true , $create_va);
        }
        return self::response(false , $create_va);
    }

    static function generate_va_payload($bank_code , $name , $price , $is_closed , $expiration_day , $is_single_true){
        $external_id = "va-".time().Utils::random();
        $payload = [
            "external_id"=>$external_id,
            "bank_code"=>$bank_code,
            "name"=>$name,
            "expected_amount"=>$price,
            "is_closed"=>$is_closed,
            "expiration_date"=> $expiration_day,
            "is_single_use"=>$is_single_true
        ];
        return $payload;
    }

    static function VirtualAccountCheckPayment($payment_id ,$external_id , $expected_amount , $merchant_code){
        $response = self::getVAPayment($payment_id);
        if($response['external_id'] == $external_id && $expected_amount == $response['amount'] && $merchant_code == $response['merchant_code']){
            return true;
        }
        return false;
    }
    
    static function getVAPayment($paymentID){
        Xendit::setApiKey(self::api_key_environment());
        $getFVAPayment = \Xendit\VirtualAccounts::getFVAPayment($paymentID);
        return $getFVAPayment;
    }
    /** END VA FUNCTIONAL */    

    /** START EWALLET FUNCTIONAL */
    static function EWalletCharge(string $order_number , int $order_id , string $currency , int $amount, string $checkout_method , string $channel_code , string $redirect , array $meta , $phone ) : array {
        Xendit::setApiKey(self::api_key_environment()); 
        $body_payload = self::EWalletGenerateBodyPayload($order_number , $currency , $amount,  $checkout_method , $channel_code , $redirect , $meta , $phone);
        $create_va = \Xendit\EWallets::createEWalletCharge($body_payload);    
        if($create_va){
            self::logs($create_va , 'create' , $order_id , 'external' );
            return self::response(true , $create_va);
        }
        return self::response(false , $create_va);
    }

    static function EWalletGenerateBodyPayload(string $order_number , string $currency , int $amount, string $checkout_method , string $channel_code , string $redirect , array $meta , $phone) : array {
        
        $body_payload = [
            'reference_id' => $order_number.'-'.uniqid(), 
            'currency' => 'IDR',
            'amount' => $amount,
            'checkout_method' => 'ONE_TIME_PAYMENT',
            'channel_code' => $channel_code,
            'channel_properties' => [
                'success_redirect_url' => 'https://hotdeal.id/payment/success',
            ],
            'metadata' => [
                'meta' => 'data'
            ]
        ];
        if($channel_code == 'ID_OVO'){
            $body_payload['channel_properties']['mobile_number'] = Utils::phoneformatE164($phone);
        }
        return $body_payload;
    }

    static function checkPaymentEwallet(){
        
    }

    static function checkEwalletPaymentStatus($charge_id){
        $response = self::EWalletChargeStatus($charge_id);
        if($response['status'] == 'SUCCEEDED'){
            return true;
        }
        return false;
    }
    
    static function EWalletChargeStatus($charge_id){ 
        Xendit::setApiKey(self::api_key_environment()); 
        $EWalletChargeStatus = \Xendit\EWallets::getEWalletChargeStatus($charge_id);
        //print_r($EWalletChargeStatus);
        return $EWalletChargeStatus;

    }

    /** END EWALLET FUNCTIONAL */
}
