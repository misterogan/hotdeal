<?php

namespace App\Http\Controllers\Api;

use App\Area;
use App\Cart;
use App\Dau;
use App\Helpers\Sms;
use App\Helpers\Utils;
use App\Hotpoint;
use App\HotpointSetting;
use App\Http\Controllers\Controller;
use App\Http\Resources\AddressResource;
use App\Http\Resources\HotPointResource;
use App\Http\Resources\ProfileResource;
use App\Notification;
use App\Order;
use App\PromotionVoucher;
use App\User;
use App\UserAddress;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Api
{
    /**
     * @OA\Post(
     * path="/api/profile/address/add",
     * summary="Profile",
     * description="insert address",
     * operationId="insert address",
     * tags={"Profile"},
     * security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="recipientname",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="phone",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="address",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="province",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="city",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="regency",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="area",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="zip_code",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="label_name",
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

    public function add_address(Request $request){
        // echo json_encode($request->all());
        // exit;
        $user = $this->user();
        $validation = Validator::make($request->all(), [
            'recipientname'         => 'required',
            'phone'                 => 'required',
            'address'               => 'required',
            'zip_code'              => 'required',
            'label_name'            => 'required',
            'province'              => 'required',
            'city'                  => 'required',
            'suburbs'               => 'required',
            'area'                  => 'required',
            'isPrimary'             => '',
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),201);
        }
        if($user){
            $primary_address = false;
            if(!UserAddress::where('user_id' , $user->id)->where('status' , 'active')->exists()){
                $primary_address = true;
            }
            if($request->isPrimary == true){
                $primary_address = true;
                UserAddress::where('user_id' , $user->id)->update(['is_primary_address'=> false , 'updated_at' => Utils::now()]);
            }
            $address = UserAddress::create([
                'user_id'       => $user->id,
                'recipient_name' => $request->recipientname,
                'phone_number' => $request->phone,
                'address' => $request->address,
                'province_id' => $request->province,
                'city_id' => $request->city,
                'regency_id' => $request->suburbs,
                'area_id' => $request->area,
                'lat'     => $request->latitude,
                'lng'     => $request->longitude,
                'zip_code' => $request->zip_code,
                'label_name' => $request->label_name,
                'status' => 'active',
                'created_at'=>date('Y-m-d H:i:s'),
                'is_primary_address' => $primary_address
            ]);
            $response['message'] = $address ;
            $response['message'] = "berhasil" ;
            return $this->successResponse($address);
        }else{
            return $this->errorResponse(static::ERROR_NOT_LOGIN,static::ERROR_NOT_LOGIN_CODE);
        }
    }

    /**
     * @OA\Post(
     * path="/api/profile/address/update",
     * summary="Profile",
     * description="update address",
     * operationId="update address",
     * tags={"Profile"},
     * security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="address_id",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="recipientname",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="phone",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="address",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="province",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="regency",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="zip_code",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="label_name",
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

    public function update_address(Request $request){

        $user = $this->user();
        if($user){
            $validation = Validator::make($request->all(), [
                'recipientname'         => 'required',
                'phone'                 => 'required',
                'address'               => 'required',
                'zip_code'              => 'required',
                'label_name'            => 'required',
                'province'              => 'required',
                'suburbs'               => 'required',
                'itemid'                => 'required'
            ]);
            
            if ($validation->fails()) {
                return $this->errorResponse($validation->errors(),'201');
            }
            $address = UserAddress::where('user_id', $user->id)->where('id' , $request->itemid)->first();
            if(!$address){
                return $this->errorResponse(self::ERROR_WHEN_DATA_NOT_FOUND , self::ERROR_WHEN_DATA_NOT_FOUND_CODE);
            }

            $address->recipient_name = $request->recipientname;
            $address->phone_number = $request->phone;
            $address->address = $request->address;
            $address->zip_code = $request->zip_code;
            $address->label_name = $request->label_name;
            $address->updated_at = date('Y-m-d H:i:s');
            $address->province_id = $request->province;
            $address->regency_id = $request->suburbs;
            $address->city_id = $request->city;
            $address->area_id = $request->area;
            $address->lng = $request->longitude;
            $address->lat = $request->latitude;

            if(!$address->save()){
                return $this->errorResponse(self::ERROR_WHEN_SAVE_DATA , self::ERROR_WHEN_SAVE_DATA_CODE);
            }
            $addressCollection = new AddressResource(UserAddress::with('province','regency')
            ->where('user_id',$user->id)->where('status','=','active')->where('id' , $request->itemid)->first());
            return $this->successResponse($addressCollection);

        }else{
            return $this->errorResponse(static::ERROR_NOT_LOGIN,static::ERROR_NOT_LOGIN_CODE);
        }

    }

    public function set_pin_point(Request $request){
        $user = $this->user();

        $primary = UserAddress::where('user_id', $user->id)->where('status', 'active')->where('is_primary_address', true)->first();
        // dd($primary, $request->latitude, $request->longitude);
        if($primary){
            $primary->lat = $request->latitude;
            $primary->lng = $request->longitude;
            $primary->save();
            if($primary->save()){
                $data = [
                    'lat' => $request->latitude,
                    'lng' => $request->longitude
                ];
                return $this->successResponse($data);

            }
        }
        return $this->errorResponse(static::ERROR_WHEN_SAVE_DATA,static::ORDER_ERROR_SAVE_DATA_CODE);
    }

    /**
     * @OA\Post(
     * path="/api/profile/address/delete",
     * summary="Profile",
     * description="delete address",
     * operationId="delete address",
     * tags={"Profile"},
     * security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="address_id",
     *          required=true,
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

    public function delete_address(Request $request){
        //print_r($request->all());
        $user = $this->user();
        if($user){
            $useraddress = UserAddress::where("id", $request->id)->where('user_id' , $user->id)->update([
                'status' => 'inactive',
                'updated_at' => Utils::now()
            ]);
            return $this->successResponse([] , 'Berhasil menghapus Alamat');
        }else{
            return $this->errorResponse(static::ERROR_NOT_LOGIN,static::ERROR_NOT_LOGIN_CODE);
        }

        return $this->errorResponse('Terjadi kesalahan saat menghapus alamat',static::ERROR_NOT_LOGIN_CODE);

    }

    /**
     * @OA\Get(
     * path="/api/profile/address/get",
     * summary="Profile",
     * description="get address",
     * operationId="get address",
     * tags={"Profile"},
     * security={ {"sanctum": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(
     *        )
     *     )
     * )
     */

    public function get_address(Request $request){

        $user = $this->user();
        if($user){
            $addresses = UserAddress::with('province','regency','city' ,'area')
                ->where('user_id',$user->id)->where('status','=','active')->orderBy('id' , 'DESC')->get();
            $data = [
                'address'=> AddressResource::collection($addresses),
            ];
            return $this->successResponse($data);

        }else{
            return $this->errorResponse(static::ERROR_NOT_LOGIN,static::ERROR_NOT_LOGIN_CODE);
        }

    }

    /**
     * @OA\Post(
     * path="/api/profile/address/primary",
     * summary="Profile",
     * description="primary address",
     * operationId="primary address",
     * tags={"Profile"},
     * security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="itemid",
     *          required=true,
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

    public function set_primary_address(Request $request){

        $user = $this->user();
        if($user){

            $primary = UserAddress::where('user_id', $user->id)->where('id',$request->itemid)->first();


            if(!$primary){
                return $this->errorResponse(self::ERROR_WHEN_DATA_NOT_FOUND , self::ERROR_WHEN_DATA_NOT_FOUND_CODE);
            }

            UserAddress::where("user_id", $user->id)->update([
                'is_primary_address' => 0,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            UserAddress::where("id", $request->itemid)->where('user_id' , $user->id)->update([
                'is_primary_address' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            $primary = UserAddress::with(['regency', 'province'])->where('user_id', $user->id)->where('is_primary_address', true)->first();

            $response['message'] = "berhasil" ;
            $response['data'] = $primary ;
            return $this->successResponse($response);

        }else{
            return $this->errorResponse(static::ERROR_NOT_LOGIN,static::ERROR_NOT_LOGIN_CODE);
        }
    }

    /**
     * @OA\Get(
     * path="/api/profile/address/primary",
     * summary="Profile",
     * description="get primary address",
     * operationId="get primary address",
     * tags={"Profile"},
     * security={ {"sanctum": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(
     *        )
     *     )
     * )
     */

    public function primary_address(Request $request){

        $user = $this->user();
        if($user){
            $address = UserAddress::where('user_id' , $user->id)->where('is_primary_address' , '1')->where('status', 'active')->first();
            if($address){
                $address = new AddressResource($address);
            }else{
                $address = [];
            }
            $data = [
                'users' => ['email' => $user->email , 'name' => $user->name , 'phone' => $user->phone],
                'address' => $address
            ];
            return $this->successResponse($data);
        }else{
            return $this->errorResponse(static::ERROR_NOT_LOGIN,static::ERROR_NOT_LOGIN_CODE);
        }
    }

    /**
     * @OA\Post(
     * path="/api/profile/change/password",
     * summary="Profile",
     * description="changepass",
     * operationId="changepass",
     * tags={"Profile"},
     * security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="oldpass",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="newpass",
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

    public function change_password(Request $request){

        $user = $this->user();

        if($user){
 
            $validation = Validator::make($request->all(), [
                'oldpass' => 'required',
                'newpass' => 'required',
            ]);
            // print_r($request->all()); 
            // print_r($user);
            if ($validation->fails()) {
                return $this->errorResponse(static::ERROR_REQUIRED_FIELD,'201');
            }

            if($user->password == ''){
                if($request->oldpass != $request->newpass){
                    return $this->errorResponse(static::ERROR_PASSWORD_NOT_MACTH,'504');
                }else{
                    $user->fill([
                        'password' => Hash::make($request->newpass)
                    ])->save();
                    $response['message'] = static::SUCCESS_CHANGE_PASSWORD ;
                    return $this->successResponse($response);
                }
            }else{
                if(Hash::check($request->oldpass, $user->password )){
                    if(strlen($request->newpass) < 6){
                        return $this->errorResponse(static::ERROR_MIN_PASSWORD, static::ERROR_MIN_PASSWORD_CODE);
                    }
        
                    if ($request->newpass != $request->confirm){
                        return $this->errorResponse(static::ERROR_PASSWORD_NOT_MACTH, static::ERROR_PASSWORD_NOT_MATCH_CODE_);
                    }
                    $user->fill([
                        'password' => Hash::make($request->newpass)
                    ])->save();
                    $response['message'] = static::SUCCESS_CHANGE_PASSWORD ;
                    return $this->successResponse($response);

                }else{
                    return $this->errorResponse(static::ERROR_PASSWORD_NOT_MATCH,static::ERROR_PASSWORD_NOT_MATCH_CODE_);
                }
            }
        }else{
            return $this->errorResponse(static::ERROR_NOT_LOGIN,static::ERROR_NOT_LOGIN_CODE);
        }
    }

    /**
     * @OA\Post(
     * path="/api/profile/update",
     * summary="Profile",
     * description="profileupdate",
     * operationId="profileupdate",
     * tags={"Profile"},
     * security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="name",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="date_of_birth",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="gender",
     *          required=false,
     *          in="query",
     *          description="male;female",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="phone_number",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="email_verification",
     *          required=false,
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

    public function update_profile(Request $request){

        

        $user = $this->user();
        if(isset($request->phone_number)){
            $check_phone_number = User::where('phone' , $request->phone_number)->first();
            if($check_phone_number){
                if($check_phone_number->email != $user->email){
                    return $this->errorResponse(static::UNIQUE_PHONE_NUMBER,static::PHONE_HAS_VERIFY_CODE);
                }
            }
        }
        if($user){
            $account = User::where("id", $user->id)->first();
            $users =  User::where("id", $user->id)->update([
                 'name' => $request->name ? $request->name : $account->name ,
                 'dob' => $request->date_of_birth ? $request->date_of_birth : $account->dob ,
                 'gender' => $request->gender ? strtolower($request->gender)  : $account->gender,
                 'phone' => $request->phone_number ? $request->phone_number : $account->phone ,
                 'email' => $request->email_verification ? $request->email : $account->email ,
            ]);
            if($user){
                return $this->successResponse($user);
            }

        }else{
            return $this->errorResponse(static::ERROR_NOT_LOGIN,static::ERROR_NOT_LOGIN_CODE);
        }
    }

    /**
     * @OA\Get(
     * path="/api/profile/get",
     * summary="Profile",
     * description="getprofile",
     * operationId="getprofile",
     * tags={"Profile"},
     * security={ {"sanctum": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(
     *        )
     *     )
     * )
     */

    public function get_profile(Request $request){
       $user = $this->user();

        if($user){
            $profile = User::with('user_addresses')
                ->where("id", $user->id)
                ->first();

            $response['profile'] = new ProfileResource($profile);
            $response['message'] = "berhasil" ;
            return $this->successResponse($response);
        }else{
            return $this->errorResponse(static::ERROR_NOT_LOGIN,static::ERROR_NOT_LOGIN_CODE);
        }

    }

    public function menubar_materials(){
        $user = $this->user();
        $now = date('Y-m-d H:i:s');
        $cart = Cart::leftJoin('product_details' ,'cart.product_details_id' , 'product_details.id')
                    ->leftJoin('products', 'product_details.product_id', 'products.id')
                    ->where('user_id' , $user->id)
                    ->where('cart.status' ,'active')
                    ->where('products.status' ,'active')
                    ->sum('quantity');
        if(Auth::check()){
            $data = array(
                'countCart' => Utils::number_for_badge($cart),
                'notification' => Utils::number_for_badge(Notification::leftJoin('notification_details','notification_details.notification_id' , 'notifications.id')->where('notification_details.user_id', $user->id)->where('notification_details.is_read' , false)->count()),
                'message' => Utils::number_for_badge(0),
                'promotion' => Utils::number_for_badge(PromotionVoucher::where('start_date','<=', $now)->where('end_date', '>=', $now)->where('apply_to_all_user', true)->where('status', 'active')->count())
            );
        }else{
            $data = array(
                'countCart' => 0,
                'notification' => 0,
                'message' => 0,
                'promotion' => 0
            );
        }
        // Add DAU
        Utils::add_dau($user->id);
        
        return $this->successResponse($data);
    }

    /**
     * @OA\Get(
     * path="/api/profile/transaction/get",
     * summary="Profile",
     * description="get transaction",
     * operationId="get transaction",
     * tags={"Profile"},
     * security={ {"sanctum": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(
     *        )
     *     )
     * )
     */

    public function get_transaction(Request $request){
        $user = $this->user();
        $total_order = Order::where('user_id',$user->id)->where('status',5)->count();
        if ($total_order > 0) {
            $last_order = Order::where('user_id', $user->id)->latest()->first();
            $total_paid_order = Order::where('user_id', $user->id)->sum('total_payment');

            $response['total_purchase'] = $total_order;
            $response['last_order_date'] = Utils::change_date($last_order->created_at);
            $response['last_order_payment'] = Utils::currency_convert($last_order->total_payment);
            $response['total_paid_order'] = Utils::currency_convert($total_paid_order);
        } else {
            $response['total_purchase'] = 0;
            $response['last_order_date'] = '-- / -- / ----';
            $response['last_order_payment'] = 0;
            $response['total_paid_order'] = 0;
        }
        return $this->successResponse($response);
    }

    /**
     * @OA\Get(
     * path="/api/profile/check/password",
     * summary="Profile",
     * description="check password",
     * operationId="check password",
     * tags={"Profile"},
     * security={ {"sanctum": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(
     *        )
     *     )
     * )
     */
    public function check_security(Request $request){
        $user = $this->user();
        if($user->password){
            $response['password'] = true;
        }else{
            $response['password'] = false;
        }
        $response['pin'] = 2;

        if(HotpointSetting::where('user_id', $user->id)->count() > 0){
            $response['pin'] = 1;
        }else{
            $response['pin'] = 0;
        }
        return $this->successResponse($response);
    }


    public function hotpoints_list(Request $request) {
        
        $user = $this->user();
        $perpage = isset($request->perpage) ? $request->perpage : 10;
        if (!$user) {
            return $this->errorResponse(self::ERROR_NOT_LOGIN, self::ERROR_NOT_LOGIN_CODE);
        }
        $query = Hotpoint::where('user_id' , $user->id);
        if($request->filter != 'all'){
            $query->where('type' , $request->filter);
        }
        $query->orderByDesc('updated_at')->get();
        $point = $query->paginate($perpage);
        $data["point"] = HotPointResource::collection($point);
        $data["pin_hot_point"] = HotpointSetting::where('user_id', $user->id)->count();
        $data['current_page'] = $point->currentPage();
        $data['total'] = $point->lastPage();

        return $this->successResponse($data);
    }

    public function save_profile_pictures(Request $request)
    {
        $user = $this->user();
        if($user){
            if(isset($request->profile_image)){
                $image = str_replace('data:image/png;base64,', '', $request->profile_image);
                $file = str_replace(' ', '+', $image);
                // $file = base64_decode($image);

                $folder = '/upload/profile/profile-pictures/'.$user->id.'/';
                $file_name = uniqid();
                $filename = $file_name .  '.webp';
                //$upload = Utils::upload_image($folder,$file,$filename);
                $upload = Utils::upload_base64_image($folder,$filename,$file);
            }
                if($upload){
                    User::where('id' , $user->id)->update([
                        'image' => $upload
                    ]);
                }
            }else{
                return $this->errorResponse('Kamu harus login' ,300);
            }
            return $this->successResponse(['image' => $upload]);
                    // $file = $request->profile_image;
                    // if($file != ''){
                    //     if(in_array($file->getClientOriginalExtension() , static::IMAGE_MIME)){
                    //         $folder = '/upload/profile/profile-pictures/'.$user->id.'/';
                    //         $file_name = uniqid();
                    //         $filename = $file_name .  '.' . $file->getClientOriginalExtension();
                    //         //$upload = Utils::upload_image($folder,$file,$filename);
                    //         $upload = Utils::upload_without_watermark($folder,$filename,$file);
                    //         if($upload){
                    //             User::where('id' , $user->id)->update([
                    //                 'image' => $upload
                    //             ]);
                    //         }
                    //     }else{
                    //         return $this->errorResponse('Format Tidak Sesuai' ,300);
                    //     }
                    // }
                    // else{
                    //     return $this->errorResponse('Gambar tidak ditemukan' ,300);
                    // }

    }

    
}
