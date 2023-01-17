<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Notify;
use App\Helpers\Orders;
use App\Helpers\Product;
use App\Helpers\Refunds;
use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderDetailResource;
use App\OrderDetail;
use App\Refund;
use App\RefundAccountBank;
use App\RefundLogs;
use App\RefundReturnConfirmation;
use App\RefundStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class RefundController extends Api
{
    /**
     * @OA\Post(
     * path="/api/refund/request",
     * summary="Refund",
     * description="Refund Request Form",
     * operationId="Refund Request Form",
     * tags={"Refund"},
     *security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="invoice_detail",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="description",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="image1",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="image2",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="image3",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="video",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="refund_type",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
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

    public function request(Request $request){
        $user = $this->user();
        $invoice_number = $request->invoice_number;
        $is_that_you = Product::is_that_you($user->id,$invoice_number);
        if($is_that_you){
            $validation = Validator::make($request->all(), [
                'image_1' => 'required',
                'description' => 'required',
                'video' => 'required',
            ]);
            if ($validation->fails()) {
                $message =  Utils::validation_response_message($validation->errors()->toArray() , 'one');
                return $this->errorResponse($message,201);
            }
            $upload_1   = '';
            $upload_2   = '';
            $upload_3   = '';
            $video      = '';
            if ($request->hasFile('image_1')) {
                $file_name = uniqid();
                $file = $request->image_1;
                $filename = $file_name . '-refund' . time() . '.' . $file->getClientOriginalExtension();
                $destinationPath = '/upload/refunds/';
                $upload_1 = Utils::upload_image($destinationPath, $file, $filename);
                if (!$upload_1) {
                    return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
                }

            }
            if ($request->hasFile('image_2')) {
                $file_name = uniqid();
                $file = $request->image_2;
                $filename = $file_name . '-refund' . time() . '.' . $file->getClientOriginalExtension();
                $destinationPath = '/upload/refunds/';
                $upload_2 = Utils::upload_image($destinationPath, $file, $filename);
                if (!$upload_2) {
                    return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
                }

            }
            if ($request->hasFile('image_3')) {
                $file_name = uniqid();
                $file = $request->image_3;
                $filename = $file_name . '-refund' . time() . '.' . $file->getClientOriginalExtension();
                $destinationPath = '/upload/refunds/';
                $upload_3 = Utils::upload_image($destinationPath, $file, $filename);
                if (!$upload_3) {
                    return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
                }

            }

            if ($request->hasFile('video')) {
                $file_name = uniqid();
                $file = $request->video;
                $filename = $file_name . '-refund' . time() . '.' . $file->getClientOriginalExtension();
                $destinationPath = '/upload/refunds/';
                $video = Utils::upload_video($destinationPath, $file, $filename);
                if (!$video) {
                    return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
                }

            }

            $order_details = OrderDetail::where('invoice_number',$invoice_number)->with('vendor.user')->first();
            $order_details_id = $order_details->id;
            $refund = Refund::create([
                'user_id'=>$user->id,
                'refund_type'=>$request->refund_type == 'point' ? 'hotpoint' : 'cash',
                'refund_status_id'=> '1',
                'order_details_id'=>$order_details_id,
                'description'=>$request->description,
                'image_1'=>$upload_1,
                'image_2'=>$upload_2,
                'image_3'=>$upload_3,
                'video'=>$video,
                'created_at'=>date('Y-m-d H:i:s'),
            ]);
            $order_details->status = 6;
            $order_details->save();
            if($refund){
                // $notify = Notify::send('REFUND', 'user telah mengajukan refund untuk invoice number' . $invoice_number, '', '', 'uid', 'Refund', $order_details->vendor->user->id, 'System');
                // VENDOR NOTIFICATION
                Notify::send(
                    Notify::$REFUND_TITLE 
                    ,$user->name .' mengajukan pengembalian untuk pesanan ' . $invoice_number . '. Mohon segera memberikan konfirmasi'
                    , '' 
                    , ''
                    , 'uid' 
                    , Notify::$REFUND_TOPIC
                    ,$order_details->vendor->user->id
                    ,'System'
                );
                // ADMIN NOTIFICATION
                Notify::send(
                    Notify::$REFUND_TITLE 
                    ,$user->name .' mengajukan pengembalian untuk pesanan ' . $invoice_number . '. Mohon segera memberikan konfirmasi'
                    , '/admin/refunds' 
                    , ''
                    , 'uid' 
                    , Notify::$REFUND_TOPIC
                    ,'0'
                    ,'System'
                );
                $refund_id = $refund->id;
                $refund_status_id = 1;
                $description = 'no description';
                $refund_log = Refunds::save_detail_log($refund_id,$refund_status_id,$description);
            }
            $response['message'] = "berhasil" ;
            return $this->successResponse($response);
        }else{
            return json_encode(['status' => false, 'message' => 'Nomor Invoice salah']);
        }
    }

    /**
     * @OA\Post(
     * path="/api/refund/return/confirmation/",
     * summary="Refund",
     * description="Refund return confirmation Form",
     * operationId="Refund return confirmation Form",
     * tags={"Refund"},
     *security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="name",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="receipt_number",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="shipping_name",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
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

    public function return_confirmation(Request $request){
        $user = $this->user();
        $invoice_number = $request->invoice_number;
        $refund = Refund::where('id', $request->refund_id)->first();
        $is_that_you = Product::is_that_you($user->id,$invoice_number);
        if($is_that_you){
            $validation = Validator::make($request->all(), [
                'consignor' => 'required',
                'receipt_number' => 'required',
                'shipping_name' => 'required',
            ]);
            if ($validation->fails()) {
                return $this->errorResponse($validation->errors(),201);
            }
            $check_if_exist  = RefundReturnConfirmation::where('refund_id' , $request->refund_id)->first();
            if($check_if_exist){
                $check_if_exist->consignor = $request->consignor;
                $check_if_exist->receipt_number = $request->receipt_number;
                $check_if_exist->shipping_name = $request->shipping_name;
                $check_if_exist->updated_at = date('Y-m-d H:i:s');
                $check_if_exist->save();

                Notify::send(
                    Notify::$REFUND_PROCESS_TITLE 
                    ,$user->name . ', telah memberikan data pengiriman barang. Paket refund dalam proses pengiriman oleh kurir'
                    , '' 
                    , ''
                    , 'uid' 
                    , Notify::$REFUND_TOPIC
                    ,$refund->detail->vendor->user_id
                    ,'System'
                );
                Notify::send(
                    Notify::$REFUND_PROCESS_TITLE 
                    ,$user->name . ', telah memberikan data pengiriman barang. Paket refund dalam proses pengiriman oleh kurir'
                    , '' 
                    , ''
                    , 'uid' 
                    , Notify::$REFUND_TOPIC
                    , '0'
                    ,'System'
                );

            }else{
                $refund_confirmation = RefundReturnConfirmation::create([
                    'consignor'=>$request->consignor,
                    'refund_id'=>$request->refund_id,
                    'receipt_number'=>$request->receipt_number,
                    'shipping_name'=>$request->shipping_name,
                    'created_at'=>date('Y-m-d H:i:s'),
                ]);
                Notify::send(
                    Notify::$REFUND_PROCESS_TITLE 
                    ,$user->name . ', telah memberikan data pengiriman barang. Paket refund dalam proses pengiriman oleh kurir'
                    , '' 
                    , ''
                    , 'uid' 
                    , Notify::$REFUND_TOPIC
                    ,$refund->detail->vendor->user_id
                    ,'System'
                );
                Notify::send(
                    Notify::$REFUND_PROCESS_TITLE 
                    ,$user->name . ', telah memberikan data pengiriman barang. Paket refund dalam proses pengiriman oleh kurir'
                    , '' 
                    , ''
                    , 'uid' 
                    , Notify::$REFUND_TOPIC
                    , '0'
                    ,'System'
                );
                $description = RefundStatus::select('description')->where('id' , 9)->first();
                $update_refund = Refunds::update_status($request->refund_id ,9 , '', false);
            }


            $response['message'] = "berhasil" ;
            return $this->successResponse($response);
        }else{
            return json_encode(['status' => false, 'message' => 'Nomor Invoice salah']);
        }

    }
    public function refund_cancel_order(Request $request){
        $validation = Validator::make($request->all(), [
            'invoice_number' => 'required',
            'refund_type' => 'required',
        ]);
        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),201);
        }
        if($request->refund_type == 'cash'){
            $validation = Validator::make($request->all(), [
                'invoice_number' => 'required',
                'bank_name' => 'required',
                'refund_type' => 'required',
                'account_number' => 'required',
                'account_name' => 'required',
                'identity_image' => 'required'
            ]);
            if ($validation->fails()) {
                return $this->errorResponse($validation->errors(),201);
            }
        }

        $user = $this->user();
        $invoice = OrderDetail::where('invoice_number' , $request->invoice_number)->with('order')->with('refund')->whereIn('status' , [8,9,10])->first();
        
        if($invoice && $user->id == $invoice->order->user_id){
            try {
                DB::beginTransaction();
                $refund  = Refund::where('order_details_id' , $invoice->id)->first();
                if($refund){
                    if($refund->refund_status_id == 12){
                        if ($request->hasFile('identity_image')) {
                            $file_name = uniqid();
                            $file = $request->identity_image;
                            $filename = $file_name . '.' . Utils::$IMAGE_CONVERT_EXT;
                            $destinationPath = '/hotdeal/upload/refund/identity/';
                            $upload = Utils::upload_without_watermark_to_webp($destinationPath, $filename ,$file );
                            if (!$upload) {
                                return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
                            }
                        }

                        $refund->refund_type = $request->refund_type;
                        $refund->save();
                        if($request->refund_type == 'cash'){
                            $refund_bank = RefundAccountBank::where('refund_id', $refund->id)->first();
                            if($refund_bank){
                                $refund_bank->account_name = $request->account_name;
                                $refund_bank->account_number = $request->account_number;
                                $refund_bank->bank_name = $request->bank_name;
                                $refund_bank->identity_image = $upload;
                                $refund_bank->updated_at = Utils::now();
                                $refund_bank->save();
                            }
                            if($refund_bank->save()){
                                $this->refund_log(12 , $refund->id , 'Mengubah form pengembalian dana');
                                return $this->successResponse();
                            }
                        }
                        $this->refund_log(12 , $refund->id , 'Mengubah form pengembalian dana');
                    }
                    return $this->successResponse();
                }else{
                    $create_refund = Refund::create(
                        [
                            'user_id' => $user->id,
                            'refund_type' => $request->refund_type,
                            'order_details_id' => $invoice->id,
                            'description' => 'Pembatalan Order',
                            'image_1' => 'null',
                            'video' => 'video',
                            'refund_status_id' => 12,
                            'created_at' => Utils::now(),
                            'updated_at' => Utils::now()
                        ]
                    );
                    if($request->refund_type == 'cash'){

                        if ($request->hasFile('identity_image')) {
                            $file_name = uniqid();
                            $file = $request->identity_image;
                            $filename = $file_name . '.' . Utils::$IMAGE_CONVERT_EXT;
                            $destinationPath = '/hotdeal/upload/refund/identity/';
                            $upload = Utils::upload_without_watermark_to_webp($destinationPath, $filename ,$file );
                            if (!$upload) {
                                return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
                            }
                        }
                        RefundAccountBank::create(
                            [
                                'user_id' => $user->id,
                                'refund_id' => $create_refund->id,
                                'account_name' => $request->account_name,
                                'account_number' => $request->account_number,
                                'bank_name' => $request->bank_name,
                                'identity_image' => $upload,
                                'updated_at' => Utils::now(),
                                'created_at' => Utils::now()
                            ]
                        );
                    }
                    $this->refund_log(12 , $create_refund->id , $request->refund_type == 'cash' ? 'Mengisi form pengembalian dana' : 'Pengembalian dana menggunakan hotpoint');
                }
                DB::commit();
                return $this->successResponse();
            } catch (Throwable $e) {    
                DB::rollBack();
                return  $this->errorResponse(static::ORDER_ERROR_SAVE_DATA, $e->getMessage());
            }
        }else{
            return $this->errorResponse('Nomor invoice tidak ditemukan' , 404);
        }
    }


    public function refund_by_invoice( Request $request){
        $invoice = OrderDetail::where('invoice_number' , $request->invoice)->first();
        $refund = [];
        if($invoice){
            $refund = Refund::select('refunds.*','refund_status.status','refund_status.description as status_description')->leftJoin('refund_status' ,'refunds.refund_status_id' , 'refund_status.id')->
            with('logs')->
            with('bank_account')->
            with('refund_confirmation')
            ->where('order_details_id' , $invoice->id)->first();
        }
        // dd($refund->logs[0]->created_at);
        return $this->successResponse(['refund' => $refund, 'transaction' => new OrderDetailResource($invoice) , 'tracker' => $response['tracker'] = ['pending'=> 'Proses penjual', 'delivery' => 'dikirim', 'arrived' => 'Pesanan Sampai']]);
    }

    /**
     * @OA\Post(
     * path="/api/refund/store/account/bank",
     * summary="Refund",
     * description="Refund insert acccount bank Form",
     * operationId="Refund insert acccount bank Form",
     * tags={"Refund"},
     *security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="account_name",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="account_number",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="bank_name",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
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
    public function store_account(Request $request){
            $user = $this->user();
            $validation = Validator::make($request->all(), [
                'account_name'      => 'required',
                'account_number'    => 'required',
                'bank_name'         => 'required',
                'refund_id'         => 'required',
                'invoice_number'    => 'required',
                'identity_image'    => 'required'
            ]);
            if ($validation->fails()) {
                return $this->errorResponse($validation->errors(),201);
            }
            //print_r($user->id); exit;
            $is_that_you = Orders::is_that_you($user->id,$request->invoice_number);
           
            if($is_that_you){
                if ($request->hasFile('identity_image')) {
                    $file_name = uniqid();
                    $file = $request->identity_image;
                    $filename = $file_name . '.' . Utils::$IMAGE_CONVERT_EXT;
                    $destinationPath = '/hotdeal/upload/refund/identity/';
                    $upload = Utils::upload_without_watermark_to_webp($destinationPath, $filename ,$file );
                    if (!$upload) {
                        return json_encode(['status' => false, 'message' => 'Failed to upload file.']);
                    }
                }

                $account_bank = RefundAccountBank::create([
                    'user_id'=>$user->id,
                    'account_name'=>$request->account_name,
                    'account_number'=>$request->account_number,
                    'bank_name'=>$request->bank_name,
                    'refund_id' => $request->refund_id,
                    'created_at'=>date('Y-m-d H:i:s'),
                    'identity_image' => $upload
                ]);
                $update_refund = Refunds::update_status($request->refund_id ,11 , '', false);
                $response['message'] = "berhasil" ;
                return $this->successResponse($response);
            }
            return $this->errorResponse('Don\'t Try Me .. ;)' , 500);

    }


    public function refund_validation(){
        
    }

    public function refund_log($status_id , $refund_id , $description){
        RefundLogs::create(
            [
                'refund_status_id' => $status_id,
                'refund_id' => $refund_id,
                'description' => $description,
                'created_at' => Utils::now(),
                'updated_at' => Utils::now()
            ]
        );
    }

}
