<?php

namespace App\Http\Controllers\Api;
use App\Cart;
use App\Helpers\Emails;
use App\Helpers\Hotpoint;
use App\Helpers\Logistics;
use App\Helpers\Notify;
use App\Helpers\Orders;
use App\Helpers\Payment;
use App\Helpers\Product;
use App\Helpers\TransactionEmailTemplate;
use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Resources\OrderResource;
use App\Jobs\ProcessExpiredOrder;
use App\Jobs\RaffleTicket;
use App\Jobs\TransactionEmailNotification;
use App\LogVoucherUsage;
use App\MediaLog;
use App\Order;
use App\OrderDetail;
use App\OrderDetailProduct;
use App\OrderDetailShipping;
use App\OrderHistory;
use App\OrderLog;
use App\OrderPayment;
use App\OrderPaymentLog;
use App\OrderStatus;
use App\OrderVouchers;
use App\PaymentMethod;
use App\ProductDetail;
use App\PromotionVoucher;
use App\User;
use App\UserAddress;
use App\Vendor;
use App\ViewOrder;
use App\ViewProduct;
use Carbon\Carbon;
use Hamcrest\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

class OrderController extends Api
{
    public function create_order(Request $request){
        $user = $this->user();
        if($user){
            if(!$user->is_phone_verified){
                return $this->errorResponse(static::PHONE_MUST_VERIFY_ERROR , static::PHONE_MUST_VERIFY_ERROR_CODE);
            }
        }
        $logistic = [];
        $merchant_voucher = [];
        $hotdeal_voucher = null;
        if(count($request->logistic) > 0){
            foreach($request->logistic as $item){
                $logistic[$item['vendor']] = $item;
                if($item['detail']['min_day'] == $item['detail']['max_day']){
                    $estimasi = $item['detail']['max_day'];
                } else {
                    $estimasi = $item['detail']['min_day'].' - '.$item['detail']['max_day'];
                }
            }
            $est = $estimasi;
        }
        
        if(count($request->merchant_voucher) > 0){
            foreach($request->merchant_voucher as $item){
                $merchant_voucher[$item['vendor_id']] = $item;
            }
        }
        if(count($request->voucher) > 0){
            foreach($request->voucher as $item){
                //$hotdeal_voucher[$item['vendor_id']] = $item;
            }
        }
        
        if(count($request->voucher_hotdeal)){
                $hotdeal_voucher = $request->voucher_hotdeal['voucher_code'];
        }
        /** Get all Product in cart **/
        $cart = Cart::select('products.name','products.weight' ,'products.dimension' ,'products.id','cart.product_details_id','products.vendor_id' ,'cart.quantity', 'cart.notes' ,'view_products.*')
            ->join('view_products' ,'view_products.product_detail_id' , 'cart.product_details_id')
            ->join('products' ,'view_products.product_id' , 'products.id' )
            ->where('user_id',$user->id)
            ->where('cart.status', 'active')
            ->where('products.status', 'active')
            ->where('in_checkout', true)
            ->get();

        $orders = [];
        $total_payment = 0;
        $total_insurance = 0;
        $payment_isurance = 0;
        $insurance_by_vendor = [];
        $all_id_product = [];
        if($cart){
            foreach($cart as $key=>$val){
                $orders[$val->vendor_id][] = $val;
                $total_payment += ($val->face_price * $val->quantity);
                $all_id_product[$val->product_id] = ($val->face_price * $val->quantity);   
            }
        }
        
        if(count($request->insurances) > 0){
            foreach($request->insurances as $key =>$item){
                if(is_array($item) > 0){
                    if($item['applied']){
                        $payment_isurance += $item['fee'];
                        $total_payment += $item['fee'];
                        $total_insurance += $item['fee'];
                        $insurance_by_vendor[$key] = $item['fee'];
                    }
                }
            }
        }
        
        try {
            DB::beginTransaction();
            if($request->point_used > 0){
                $validate_point = Hotpoint::check_used_point($user , $request->point_used);
                if(!$validate_point){
                    return $this->errorResponse(self::PIN_WRONG , self::PIN_WRONG_CODE);
                }
            }
            for ($i = 1 ; $i <= 5; $i++){
                $transaction_number =  Utils::transaction_number($user->id);
                $check_trx = Order::where('transaction_number', $transaction_number)->first();
                if(!$check_trx){
                    $transaction_number = $transaction_number;
                }
            }
            
            $order_data = Order::create([
                'user_id'=>$user->id,
                'transaction_number'=> $transaction_number ,
                'total_payment'=> $total_payment,
                'total_insurance' => $total_insurance,
                'total_discount'=>0,
                'point' => $request->point_used
            ]);
            
            OrderStatus::create([
                'master_status_order_id' => 1,
                'order_id' => $order_data->id,
                'created_at' => Utils::now()
            ]);
            $disc_hotdeal = 0;
            $total_disc_hotdeal = 0;
            $total_logistic_price = 0;

            foreach($orders as $key=>$val){
                $total_payment_by_vendor = $this->total_payment_by_vendor($val);
                $discount_by_vendor = $this->check_discount_by_vendor($key , $merchant_voucher , $total_payment_by_vendor);
                
                 // Decrease total payment
                $total_payment -= $discount_by_vendor['value'];
                if($discount_by_vendor['status'] == false){
                    return $this->errorResponse(static::ORDER_VOUCHER_ERROR_SAVE_DATA , static::ORDER_VOUCHER_ERROR_SAVE_DATA_CODE);
                }

                $invoice_number = Utils::invoice_number();
                /** INSERT TO ORDER DETAIL */
                $oder_detail_data = OrderDetail::create([
                    'order_id' => $order_data->id,
                    'vendor_id'=> $key,
                    'invoice_number'=> $invoice_number,
                    'insurance_fee' =>  array_key_exists($key , $insurance_by_vendor) ? $insurance_by_vendor[$key] : 0,
                    'invoice_total_payment'=> $total_payment_by_vendor,
                    'invoice_total_discount'=> $discount_by_vendor['value'],
                    'estimasi' => $est,
                    'created_at'=> date('Y-m-d H:i:s'),
                ]);
                /** INSERT TO ORDER VOUCHER */
                if(count($discount_by_vendor['detail']) > 0){
                    $order_voucher = OrderVouchers::create([
                        'order_id' => $order_data->id,
                        'detail_voucher' => json_encode($discount_by_vendor['detail']),
                        'vendor_id' => $key,
                        'voucher_id' => $discount_by_vendor['detail']['voucher_id'],
                        'voucher_value' => $discount_by_vendor['detail']['voucher_value'],
                        'created_at' => Utils::now(),
                    ]);
                }
                $order_detail_shipper = $this->shipping_by_vendor($logistic , $key , $user , $invoice_number ,$val);
                // Add logistic payment
                // dd($key);
                $total_payment += $logistic[$key]['price'];
                $total_logistic_price += $logistic[$key]['price'];
                
                /** INSERT TO SHIPPING */
                $shipping = OrderDetailShipping::create(
                    [
                        'consignee' => json_encode($order_detail_shipper['consignee']),
                        'consigner' => json_encode($order_detail_shipper['consigner']),
                        'rate' => json_encode($order_detail_shipper['rate']),
                        'logistic_detail' => json_encode($order_detail_shipper['logistic']),
                        'shipment_services_id' => $order_detail_shipper['logistic']['courier']['rate_id']['rate_id'],
                        'order_details_id' => $oder_detail_data->id,
                        'shipping_cost' => $logistic[$key]['price']
                    ]
                );
                /** INSERT TO ORDER PRODUCT */
                foreach($val as $key2=>$val2){
                    //check stock of product
                    $stock = Product::stock($val2->product_details_id , $val2->quantity);
                    if(!$stock){
                        return  $this->errorResponse(static::OUT_OF_STOCK, static::OUT_OF_STOCK_CODE);
                    }
                    $order_detail_product = OrderDetailProduct::create([
                        'order_detail_id'=>$oder_detail_data->id,
                        'product_detail_id'=> $val2->product_details_id,
                        'quantity'=>$val2->quantity,
                        'price'=>$val2->price,
                        'discount_price'=> $val2->value_discount ? $val2->value_discount : 0,
                        'fix_price'=>$val2->face_price,
                        'admin_fee'=>$val2->admin_fee,
                        'promotion_voucher_id'=>0,
                        'promotion_discount_id'=>0,
                        'status'=>'active',
                        'notes' => $val2->notes,
                        'created_at'=> date('Y-m-d H:i:s'),
                    ]);
                    // DESCREASE STOCK OF PRODUCT
                    $update_stock = ProductDetail::where('id' , $val2->product_details_id)->decrement('stock' , $val2->quantity);
                }

            }
            // dd($total_payment); -> udah bener = 65074
             // CHECK VOUCHER BY HOTDEAL
            if($hotdeal_voucher != null){
                // if($payment_isurance > 0){ 
                //     $total_payment = $total_payment - $payment_isurance;
                // }
                
                $disc_hotdeal = $this->validate_voucher_hotdeal($hotdeal_voucher , $total_payment, $total_logistic_price, $payment_isurance , $all_id_product);
                
                // dd($disc_hotdeal);
                $total_disc_hotdeal += $disc_hotdeal;
                $total_payment -= $disc_hotdeal;

                // if($payment_isurance > 0){ 
                //     $total_payment = $total_payment + $payment_isurance;
                // }

                // save voucher data
                if($total_disc_hotdeal > 0){
                    $coupons = PromotionVoucher::where('voucher_code',$hotdeal_voucher)->first();
                    $order_voucher = OrderVouchers::create([
                        'order_id' => $order_data->id,
                        'detail_voucher' => json_encode($coupons),
                        'vendor_id' => 0,
                        'voucher_id' => $coupons->id,
                        'voucher_value' => $total_disc_hotdeal,
                        'created_at' => Utils::now(),
                    ]);
                    if($order_voucher){
                        if($coupons->is_code == true){
                            $coupons->total -= 1;
                            $coupons->save();
                        }
                    }
                }
            }
            
            if($request->point_used > 0){
                $total_payment = round($total_payment) - $request->point_used;
            }
            // dd($total_payment, $request->total_payment);
            
            if($total_payment == $request->total_payment){
                Order::where('id' ,$order_data->id)->where('user_id', $user->id)->update([
                    'total_payment' => $total_payment,
                    'total_discount' => $total_disc_hotdeal
                ]);
                Cart::where('user_id' , $user->id)->where('in_checkout', true)->update([
                    'quantity' => 0,
                    'status' =>'deleted',
                    'in_checkout' => false,
                ]);
                if((int)($total_payment) == 0 && (int)($request->point_used) > 0){
                    $payment = $this->payment_with_full_point($order_data->id , $request->point_used , $user->name , $transaction_number , $user->id);
                    if(!$payment){
                        return $this->errorResponse(self::HOTPOINT_CANT_USE , self::HOTPOINT_CANT_USE_CODE);
                    }
                    $create_payment['use_redirect'] = true;
                    $create_payment['url'] = '/payment-success';
                }else{
                    // Decrease hot Point
                    if($request->point_used > 0){
                        $hot_dec = Hotpoint::using_point($user->id , $request->point_used , 'UTR001' , 'Pemakaian untuk transaksi '.$transaction_number);
                        if(!$hot_dec){
                            return $this->errorResponse(self::HOTPOINT_CANT_USE , self::HOTPOINT_CANT_USE_CODE);
                        }
                    }
                    // CREATE PAYMENT

                    $create_payment = $this->payment_method($request->payment_gateway, $user->name , $total_payment,$order_data->id, $transaction_number, $request->phone);
                    
                    if(!$create_payment['status']){
                        return $this->errorResponse(static::ORDER_ERROR_SAVE_DATA , static::ORDER_ERROR_SAVE_DATA_CODE);
                    }
                }
                
                DB::commit();
                // QUEUE FOR EXPIRED TRANSACTION
                $details['transaction_number'] = $transaction_number;
                $details['voucher_code'] = null;
                if($hotdeal_voucher != null){
                    $details['voucher_code'] = $coupons->voucher_code;
                }
                // dd($request->payment_gateway);
                if($request->payment_gateway == null){
                    dispatch(new ProcessExpiredOrder($details))->delay(now()->addHours(24));
                } else {
                    dispatch(new ProcessExpiredOrder($details))->delay(Orders::expired_order($request->payment_gateway));
                }

                dispatch(new TransactionEmailNotification($transaction_number , 'create_order'));
                // Orders::expired_order($request->payment_gateway);


                // ProcessExpiredOrder::dispatch($details)->delay(Orders::expired_order($request->payment_gateway));
                // ProcessExpiredOrder::dispatch($transaction_number, $coupons->voucher_code)->delay(Orders::expired_order($request->payment_gateway));
                // TransactionEmailNotification::dispatch($transaction_number , 'create_order');

            }else{
                DB::rollBack();
                return  $this->errorResponse('Transaksi tidak dapat diproses', static::ORDER_ERROR_TOTAL_PAYMENT_NOT_MATCH_CODE);
                // code below is to check mismatch total payment
                // return  $this->errorResponse($total_payment.'-->'.$request->total_payment, static::ORDER_ERROR_TOTAL_PAYMENT_NOT_MATCH_CODE);

                //return  $this->errorResponse(static::ORDER_ERROR_TOTAL_PAYMENT_NOT_MATCH, static::ORDER_ERROR_TOTAL_PAYMENT_NOT_MATCH_CODE);
            }
        } catch (Throwable $e) {    
            DB::rollBack();
            // Dont miss save the logs
            return  $this->errorResponse(static::ORDER_ERROR_SAVE_DATA, $e->getMessage());
        }
        if($create_payment['use_redirect']){
             redirect()->away($create_payment['url']);
        }
        $response_message = ['order_data' =>$this->order_by_id($order_data->id) , 'redirect' => $create_payment['use_redirect'] , 'url' => $create_payment['url']];
        return $this->successResponse($response_message);
    }

    public function payment_method($payment_gateway , $name , $total_payment , $order_id, $transaction_number  , $phone) : array {        
        $check_channel_payment = PaymentMethod::where('code' , $payment_gateway)->first();
        $using_redirect = false;
        $redirect_url = '';
        if(!$check_channel_payment){
            return ['status' => false];
        }
        
        if($check_channel_payment->channel == 'E-WALLET'){
            $using_redirect = true;
            $redirect_url = '/payment-pending';
            $expiration_date = Carbon::now()->addMinutes(30)->toISOString();
            if($payment_gateway != 'ID_OVO'){
                
                $cod = Payment::EWalletCharge($transaction_number , $order_id,  '' , $total_payment ,'', $payment_gateway, 'url' , [] , $phone);
                $redirect_url = $cod['data']['actions']['desktop_web_checkout_url'];
            }else{
                $expiration_date = Carbon::now()->addMinutes(1)->toISOString();
                $cod = Payment::EWalletCharge($transaction_number , $order_id,  '' , $total_payment ,'', $payment_gateway, 'url' , [] , $phone);
                //$redirect_url = '';
            }
            if($cod['status']){
                $order = $this->create_order_payment(
                    $order_id,
                    $cod['data']['reference_id'], // va is external_id
                    $cod['data']['channel_code'], // va is bank_code
                    $name,
                    $cod['data']['charge_amount'],
                    null,
                    $expiration_date,
                    null,
                    $cod['data']['status'],
                    $cod['data']['currency'],
                    $cod['data']['business_id'], // va is busisness_id
                    null,
                    null,
                    $cod['data']['id'],
                    'Xendit'
                );
                if(!$order){
                    return ['status' => false];
                }
            }
        }else{
            $cod =  Payment::create_va(Payment::generate_va_payload($payment_gateway, $name , $total_payment , true , Carbon::now()->addDays(1)->toISOString() ,true) , $order_id);
            if($cod['status']){
                $order = $this->create_order_payment(
                    $order_id,
                    $cod['data']['external_id'],
                    $cod['data']['bank_code'],
                    $cod['data']['name'],
                    $cod['data']['expected_amount'],
                    $cod['data']['is_closed'],
                    $cod['data']['expiration_date'],
                    $cod['data']['is_single_use'],
                    $cod['data']['status'],
                    $cod['data']['currency'],
                    $cod['data']['owner_id'],
                    $cod['data']['merchant_code'],
                    $cod['data']['account_number'],
                    $cod['data']['id'],
                    'Xendit'
                );
                if(!$order){
                    return ['status' => false];
                }
                Notify::send(Notify::$PAYMENT_TITLE , str_replace(
                array("{name}", "{nominal}" , "{bank}" ,"{account}" , "{expired}"),
                array($cod['data']['name'],Utils::currency_convert($cod['data']['expected_amount']),$cod['data']['bank_code'], $cod['data']['account_number'] , date( 'Y-m-d H:i:s', strtotime($cod['data']['expiration_date']))),
                Notify::$PAYMENT_BODY), Notify::$PAYMENT_URL , '', 'uid' , Notify::$PAYMENT_TOPIC , Auth::user()->id, Notify::$PAYMENT_SEND_BY);
            }
        }

        return  ['status' => true , 'use_redirect' => $using_redirect , 'url' => $redirect_url];;
    }
    public function create_order_payment(
        $order_id , $external_id , $bank_code , 
        $user_name , $amount , $is_closed , 
        $expired , $is_single_use , $status , $currency , $owner_id , $merchant_code , $account_number,$id_payment,$payment_gateway){
        $create_order = OrderPayment::create(
            [
                'order_id' => $order_id,
                'external_id' => $external_id,
                'bank_code'=> $bank_code,
                'name'=> $user_name,
                'expected_amount' => $amount,
                'is_closed' => $is_closed,
                'expiration_date' => $expired,
                'is_single_use' => $is_single_use,
                'status' => $status,
                'currency' => $currency,
                'owner_id' => $owner_id,
                'merchant_code' => $merchant_code,
                'account_number' => $account_number,
                'id_payment' => $id_payment,
                'payment_gate' => $payment_gateway
            ]
        );
        if(!$create_order){
            return false;
        } 
        return true;
    }

    public function payment_with_full_point($order_id , $total_payment , $name , $transaction_number , $user_id){
        $payment = OrderPayment::create(
            [
                'order_id' => $order_id,
                'external_id' => 'hotpoint-'.uniqid(),
                'bank_code'=> 'hotpoint',
                'name'=> 'hotpoint',
                'expected_amount' => $total_payment,
                'is_closed' => true,
                'expiration_date' => '',
                'is_single_use' => '',
                'status' => 'SUCCESS',
                'currency' => 'POINT',
                'owner_id' => '',
                'merchant_code' => 0000,
                'account_number' =>'',
                'id_payment' => '',
                'payment_gate' => 'hotpoint'
            ]
        );
        if(!$payment){
            return false;
        }
        // decrease point 
        $hot_dec = Hotpoint::using_point($user_id , $total_payment , 'UTR001' , 'Pemakaian untuk transaksi '.$transaction_number);
        if(!$hot_dec){
            return false;
        }

        // update order log detail 
        Order::where('id' , $order_id)->update([
            'status' => 2
        ]);
        $order_log = OrderLog::create([
            'order_id'=>$order_id,
            'status_id'=>2,
            'created_at'=>Carbon::now()
        ]);

        $order_details_ = Orders::update_status_detail_complete_by_order_id($order_id , 2);  
        
        // send notification
        if($payment){
            Notify::send(Notify::$PAYMENT_SUCCESS_TITLE , str_replace(
                array("{name}", "{transaction_number}"),
                array($name, $transaction_number),
                Notify::$PAYMENT_SUCCESS_BODY),
                Notify::$PAYMENT_SUCCESS_URL , '', 'uid' , Notify::$PAYMENT_TOPIC , $user_id, Notify::$PAYMENT_SEND_BY);
            Notify::send(Notify::$PAYMENT_SUCCESS_TITLE , str_replace(
                array("{name}", "{transaction_number}"),
                array('Admin', $transaction_number),
                Notify::$ADMIN_PAYMENT_SUCCESS_BODY),
                '/admin/order' , '', 'uid' , Notify::$PAYMENT_TOPIC , '0', Notify::$PAYMENT_SEND_BY);
        }
        $get_vendor = OrderDetail::with('vendor')->where('order_id', $order_id)->get();
        foreach ($get_vendor as $data) {
            $user = User::find($data->vendor->user_id);
            $send = Emails::send_email($data->vendor->name, $user->email, 'Order Payment', $transaction_number, Emails::$VENDOR_PAYMENT);
            if(!Notify::send(Notify::$PAYMENT_SUCCESS_TITLE , str_replace(
                array("{name}", "{transaction_number}"),
                array($data->vendor->name, $transaction_number),
                Notify::$VENDOR_PAYMENT_SUCCESS_BODY), Notify::$PAYMENT_SUCCESS_URL , '', 'uid' , Notify::$PAYMENT_TOPIC , $data->vendor->user_id, Notify::$PAYMENT_SEND_BY)){
                    return $this->errorResponse(static::PAYMENT_FAILED,static::PAYMENT_FAILED_CODE);
                } 
        }
        
        RaffleTicket::dispatch($order_id);
        
        return true;
    }

    public function check_discount_by_vendor($vendor , $merchant_voucher , $total_payment){
        $value = 0;
        $detail = [];
        if(array_key_exists($vendor , $merchant_voucher)){
            if(array_key_exists('item' , $merchant_voucher[$vendor]) && array_key_exists('vendor_id' , $merchant_voucher[$vendor])){
                $item = $merchant_voucher[$vendor]['item'];
                $real_value = $this->validate_merchant_voucher($total_payment , $item['voucher_code'] ,$item['voucher_id'] , $item['vendor_id']);
                if($real_value['status'] == false){
                    return ['detail' => $real_value['msg'] , 'value' => $value , 'status' => false];
                }
                if((int)($real_value['msg']) != (int)($item['voucher_value'])){
                    return ['detail' => $detail , 'value' => $value , 'status' => false];
                }
                $value = $item['voucher_value'];
                $detail = $item;
            }

        }
        return ['detail' => $detail , 'value' => $value , 'status' => true];
    }

    public function total_payment_by_vendor($items){
        $total = 0;
        foreach($items as $item){
            $total += ($item->face_price * $item->quantity);
        }
        return $total;
    }

    public function payload_logistic(){
        return array(
            "destination"                => array(
                "area_id"=>4711,
                "lat"=>"-6.2195686",
                "lng"=>"106.8325872",
                "suburb_id"=> 482
            ),
            "origin"           => array(
                "area_id"=>4711,
                "lat"=>"-6.2195686",
                "lng"=>"106.8325872",
                "suburb_id"=> 482
            ),
            "for_order"             => true,
            "cod"                   => false,
            "height"                =>10,
            "length"                =>10,
            "width"                 =>10,
            "weight"                => 0.5,
            "item_value"            =>40000,
            "limit"                 =>30,
            "page"                  =>1,
            "sort_by"               => array('final_price')
        );
    }

    public function validate_merchant_voucher($total_payment , $voucher_code , $voucher_id , $vendor_id){
        $now = date('Y-m-d H:i:s');
        $user = $this->user();
        if($voucher_code != ''){
            $coupons = PromotionVoucher::where('vendor_id',$vendor_id)->where('voucher_code',$voucher_code)->first();
        }else if($voucher_id != ''){
            $coupons = PromotionVoucher::where('id',$voucher_id)->first();
        }
        if(!$coupons){
            return ['status' => false, 'msg' => static::COUPON_NOT_FOUND];
        }
        else if( $now < $coupons->start_date){
            return ['status' => false, 'msg' => static::COUPON_NOT_STARTED];
        }
        else if( $now > $coupons->end_date){
            return ['status' => false, 'msg' => static::COUPON_EXPIRED];
        }
        else if($coupons->apply_to_all_user == false && $coupons->user_id != $user->id ){
            return ['status' => false, 'msg' => static::COUPON_NOT_AVAILABLE];
        }
        else if($total_payment < $coupons->minimum_payment ){
            return ['status' => false, 'msg' => static::COUPON_NOT_MATCH];
        }
        if($coupons->discount_type == 'nominal'){
            return ['status' => true, 'msg' => $coupons->value_discount];
        }else{
            $discount_amount = $total_payment * floatval($coupons->value_discount) / 100;

            if($discount_amount > $coupons->maximum_promo){
                $discount_amount = $coupons->maximum_promo;
            }
        }
        return ['status' => true, 'msg' => $discount_amount];
    }

    public function validate_voucher_hotdeal($voucher , $total_payment, $total_logistic_price, $payment_isurance , $all_id_product){
        $now = date('Y-m-d H:i:s');
        $user = $this->user();
        $coupons = PromotionVoucher::where('voucher_code',$voucher)->first();
        $check_voucher_code = Order::select('detail_voucher', 'order_payments.updated_at', 'order_payments.status', 'order_payments.id')
                                    ->leftJoin('order_vouchers', 'orders.id', 'order_vouchers.order_id')
                                    ->leftJoin('order_payments', 'orders.id', 'order_payments.order_id')
                                    ->where('user_id', $user->id)
                                    ->where('order_payments.status', 'SUCCESS')
                                    ->whereBetween('order_payments.updated_at', [Carbon::now()->subHours(48), Carbon::now()])
                                    ->get();
        $voucher_checked = false;
        $time_check_voucher = false;
        foreach ($check_voucher_code as  $value) {
            $val = json_decode($value->detail_voucher);
            if($val == null){
                continue;
            }
            if($val->voucher_code == $voucher){
                $time_check_voucher = true;
                $voucher_checked = true;
                break;
            }
        }

        $is_multiple = $coupons->is_multiple;
        if($coupons->is_code && $time_check_voucher){
            return $this->errorResponse(static::COUPON_IS_EMPTY,static::COUPON_NOT_FOUND_CODE);
        } else if(!$is_multiple && $voucher_checked){
            return $this->errorResponse(static::COUPON_HAS_USED,static::COUPON_NOT_FOUND_CODE);
        } else if($coupons->is_code && $coupons->total < 1){
            return $this->errorResponse(static::COUPON_NOT_AVAILABLE, static::COUPON_NOT_FOUND_CODE);
        }
        if(!$coupons){
            return $this->errorResponse(static::COUPON_NOT_FOUND,static::COUPON_NOT_FOUND_CODE);
        }
        else if( $now < $coupons->start_date){
            return $this->errorResponse(static::COUPON_NOT_STARTED,static::COUPON_NOT_STARTED_CODE);
        }
        else if( $now > $coupons->end_date){
            return $this->errorResponse(static::COUPON_EXPIRED,static::COUPON_NOT_FOUND_CODE);
        }
        else if($coupons->apply_to_all_user == false && $coupons->user_id != $user->id ){
            return $this->errorResponse(static::COUPON_NOT_AVAILABLE,static::COUPON_NOT_AVAILABLE_CODE);
        }
        else if($total_payment < $coupons->minimum_payment ){
            return $this->errorResponse(static::COUPON_NOT_MATCH,static::COUPON_NOT_MATCH_CODE);
        }
        
        if($coupons->amount_product_only){
            if($payment_isurance > 0){
                $total_payment -= $payment_isurance;
            }
            
            $total_payment = $total_payment - $total_logistic_price;
        }

        $exclude_discount = 0;

        
        if($coupons->apply_to_all_product == false){
            $is_product = false;
            foreach($coupons->products as $coupon){
               if(array_key_exists($coupon->product_id , $all_id_product)){
                    $is_product = true;
                    $exclude_discount += $all_id_product[$coupon->product_id];
               }
            }
            if(!$is_product){
                return $this->errorResponse('Voucher hanya untuk Jenis produk tertentu.', static::COUPON_NOT_MATCH_CODE);
            }
            if($coupons->amount_product_only){
                $total_payment = $total_payment - ($total_payment - $exclude_discount);
            }
        }

        
        if($coupons->discount_type == 'nominal'){
            $calculation_price = $total_payment - floatval($coupons->value_discount);
            $discount_amount = $coupons->value_discount;
            if($calculation_price < 1){
                $calculation_price = 0;
                $discount_amount = $total_payment;
            }
        }else{
            $discount_amount = $total_payment * floatval($coupons->value_discount)  / 100;
            
            if($discount_amount > intval($coupons->maximum_promo) && $coupons->maximum_promo != 0){
                $discount_amount = $coupons->maximum_promo;
            }
            $calculation_price = $total_payment - $discount_amount;
        }

        return $discount_amount;
    }

    public function shipping_by_vendor($shipping , $vendor_id , $user , $invoice_number , $product){

        if(!array_key_exists($vendor_id , $shipping)){
            return ['status' => false , 'msg' => 'Pengiriman belum dipilih'];
        }
        $shipper_package =  Logistics::set_package_shipper($product);;
        
        $consigner = Vendor::select('vendors.*' , 'users.phone', 'areas.api_id as areas_api' , 'suburbs.api_id as suburbs_api')
        ->join('users' , 'users.id' , 'vendors.user_id')
        ->leftJoin('areas' , 'vendors.area_id','areas.id')
        ->leftJoin('suburbs' , 'vendors.suburb_id','suburbs.id')
        ->where('vendors.id' , $vendor_id)->first();
        
        $consignee = UserAddress::select('user_addresses.recipient_name','user_addresses.address','user_addresses.area_id','user_addresses.lng','user_addresses.lat','user_addresses.phone_number','provinces.name as province_name',
        'cities.name as city_name','suburbs.name as suburbs_name','areas.name as area_name' , 'areas.api_id as areas_api' ,'suburbs.api_id as suburbs_api')
            ->leftJoin('provinces' , 'user_addresses.province_id','provinces.api_id')
            ->leftJoin('cities' , 'user_addresses.city_id','cities.id')
            ->leftJoin('suburbs' , 'user_addresses.regency_id','suburbs.id')
            ->leftJoin('areas' , 'user_addresses.area_id','areas.id')
            ->where('user_id' , $user->id)
            ->where('is_primary_address' , true)
            ->first();
        
        $consigner_data = [
            'name' => $consigner->name,
            'address' => $consigner->address,
            'area_id' => $consigner->area_id,
            'lat' => $consigner->lat,
            'lng' => $consigner->lng,
            'phone_number' => $consigner->phone
        ];
        
        $consignee_data = [
            'name' => $consignee->recipient_name,
            'address' => $consignee->address.', '.$consignee->province_name.', '.$consignee->city_name.', '.$consignee->suburbs_name.', '.$consignee->area_name,
            'area_id' => $consignee->areas_api,
            'lat' => $consignee->lat,
            'lng' => $consignee->lng,
            'phone_number' => $consignee->phone_number
        ];

        $logistic_data = [
            'consignee' => [
                'name' => $consignee->recipient_name,
                'phone_number' => $consignee->phone_number
                
            ],
            'consigner' => [
                'name' => $consigner->name,
                'phone_number' => $consigner->phone
            ],
            'courier' => [
                'cod' => false,
                'rate_id' => $shipping[$vendor_id],
                'use_insurance' => true
            ],
            'coverage' => 'domestic',
            'origin' => [
                'address' => $consigner->address,
                'area_id' => $consigner->area_id,
                'lat' => $consigner->lat,
                'lng' => $consigner->lng,
            ],
            'external_id' => $invoice_number,
            'destination' => [
                'address' => $consignee->address,
                'area_id' => $consignee->areas_api,
                'lat' => $consignee->lat,
                'lng' => $consignee->lng,
            ],
            'package' => [
                'height' => $shipper_package['height'],
                'items' => $shipper_package['items'] ,
                'length' =>  $shipper_package['length'],
                'package_type' =>  $shipper_package['package_type'],
                'price' => $shipper_package['price'],
                'weight' => $shipper_package['weight'],
                'width' => $shipper_package['width']
            ],
            'payment_type' => 'postpay'
        ];
        return [
            'consignee' => $consignee_data,
            'consigner' => $consigner_data,
            'logistic' => $logistic_data,
            'rate' => $shipping[$vendor_id]['detail'],
        ];
    }

    public function set_package_shipper($items){

        $product = [];
        $height = 0;
        $length = 0;
        $package_type = 2;
        $price  = 0;
        $weight = 0;
        $width = 0;
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
            $weight += $item->weight;
            $width += (int)($dimension[0]);
            $length += (int)($dimension[1]);
            $height += (int)($dimension[2]);
        }

        return [
            'height' => $height,
            'width' => $width,
            'items' => $product,
            'length' => $length,
            'weight' => (int)($weight) / 1000,
            'price' => $price,
            'package_type' => $package_type
        ];

    }
    /**
     * @OA\Post(
     * path="/api/order/get",
     * summary=" Order",
     * description="get order by user",
     * operationId="get order by user",
     * tags={"Order"},
     * security={ {"bearer": {} }},
     *     @OA\Parameter(
     *          name="user_id",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="status_id",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(
     *        )
     *     )
     * )
     */
    public function get_order_by_user(Request $request){
        $orders = Order::with('order_detail',
            'order_detail.order_products',
            'order_detail.order_products.gallery',
            'order_detail.order_products.product_detail')
            ->where('user_id',$request->user_id)->get();
        $response['orders'] =  OrderResource::collection($orders);
        return $this->successResponse($response);
    }

    public function order_by_id($order_id){
        $order =  Order::select('orders.transaction_number','orders.total_payment','order_payments.expected_amount','order_payments.expiration_date','order_payments.account_number','order_payments.bank_code','order_payments.is_closed','order_payments.payment_gate')
        ->leftJoin('order_payments' , 'order_payments.order_id', 'orders.id' )->where('orders.id' , $order_id)->first();
        if($order){
            return [
                'transaction_number' => $order->transaction_number,
                'total_payment' => $order->total_payment,
                'expected_amount' => $order->expected_amount,
                'expiration_date' => date('Y-m-d H:i:s' , strtotime($order->expiration_date)),
                'account_number' => $order->account_number,
                'bank_code' => $order->bank_code,
                'is_closed' => $order->is_closed,
                'pg' => $order->payment_gate
            ];
        }else{
            return [];
        }
    }

    public function pending_order_by_transaction(Request $request){
        $order =  Order::select('orders.transaction_number','orders.total_payment','order_payments.expected_amount','order_payments.expiration_date','order_payments.account_number','order_payments.bank_code','order_payments.is_closed','order_payments.payment_gate')
        ->leftJoin('order_payments' , 'order_payments.order_id', 'orders.id' )->where('orders.transaction_number' , $request->transaction_id)->first();
        if($order){
            return $this->successResponse([
                'transaction_number' => $order->transaction_number,
                'total_payment' => $order->total_payment,
                'expected_amount' => $order->expected_amount,
                'expiration_date' => date('Y-m-d H:i:s' , strtotime($order->expiration_date)),
                'account_number' => $order->account_number,
                'bank_code' => $order->bank_code,
                'is_closed' => $order->is_closed,
                'pg' => $order->payment_gate
            ]);
        }else{
            return $this->errorResponse('Transaction not found' , 404);
        }
    }

}
