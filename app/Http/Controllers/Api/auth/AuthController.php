<?php

namespace App\Http\Controllers\Api\auth;

use App\Backlink;
use App\Dau;
use App\Helpers\Emails;
use App\Helpers\OTP;
use App\Helpers\Utils;
use App\Http\Controllers\Api\Api;
use App\Nru;
use App\OtpCode;
use App\PasswordReset;
use App\User;
use App\UserActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Jenssegers\Agent\Agent;

class AuthController extends Api
{
    /**
     * @OA\Post(
     * path="/api/register",
     * summary="register",
     * description="userregister",
     * operationId="register",
     * tags={"Auth"},
     * security={ {"bearer": {} }},
     *       @OA\Parameter(
     *          name="name",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="email",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *    @OA\Parameter(
     *          name="password",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *      @OA\Parameter(
     *          name="password_confirmation",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *      @OA\Parameter(
     *          name="user_type",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer",
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

    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:120',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            //'password_confirmation' => 'required',
        ],[
            'name.required' => 'Nama Tidak boleh kosong',
            'password.min' => 'Password minimal 8 karakter',
            'password.required' => 'Password Tidak boleh kosong',
            'name.required' => 'Nama Lengkap Tidak boleh kosong',
            'name.max' => 'Nama Maksimal 120 Karakter',
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors()->first(),'201');
        }

        $agent = new Agent();
        $browser = $agent->browser();
        $platform = $agent->platform();
        $parent_id = null;
        if (isset($_COOKIE['referal_code'])){
            $referal_code = trim($_COOKIE['referal_code']);
            $referer = User::where('referal_code', $referal_code)->first();
            if($referer){
                $parent_id = $referer->id;
                Backlink::create([
                    'ip_address' => $request->ip(),
                    'referer' => $request->server('HTTP_REFERER'),
                    'referal_code' => $referal_code,
                    'device' => $platform,
                    'user_agent' => $request->header('User-Agent')
                ]);
            }
        } else {
            $parent_id = null;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'is_email_verified' => true,
            'email_verified_at' => Utils::now(),
            'password' => Hash::make($request->password),
            'parent_id' => $parent_id
        ]);

        Utils::add_nru($user->id);

        $utm_id = '';
        $utm_source = '';
        $utm_term = '';
        $utm_medium = '';
        $utm_campaign = '';
        $referal = '';
        if (isset($_COOKIE['utm_id'])){
            $utm_id = trim($_COOKIE['utm_id']);
        }
        if (isset($_COOKIE['utm_source'])){
            $utm_source = trim($_COOKIE['utm_source']);
        }
        if (isset($_COOKIE['utm_term'])){
            $utm_term = trim($_COOKIE['utm_term']);
        }
        if (isset($_COOKIE['utm_medium'])){
            $utm_medium = trim($_COOKIE['utm_medium']);
        }
        if (isset($_COOKIE['utm_campaign'])){
            $utm_campaign = trim($_COOKIE['utm_campaign']);
        }
        if (isset($_COOKIE['referal'])){
            $referal = trim($_COOKIE['referal']);
        }

        UserActivity::create([
            'user_id' => $user->id,
            'platform'=> $platform,
            'browser'=>$browser,
            'ip_address'=>$request->ip(),
            'utm_id' => $utm_id == "undefined" ? '' : $utm_id,
            'utm_source' => $utm_source == "undefined" ? '' : $utm_source,
            'utm_term' => $utm_term == "undefined" ? '' : $utm_term,
            'utm_medium' => $utm_medium == "undefined" ? '' : $utm_medium,
            'utm_campaign' => $utm_campaign == "undefined" ? '' : $utm_campaign,
            'referal' => $referal == "undefined" ? '' : $referal,
            'activity' => 'register',
        ]);


        if(!Auth::attempt(['email' => $request->email, 'password' => $request->password, 'status' => 'active'] , false)){
            return $this->successResponse([] , 'failed-login' , 200);
        };
        return $this->successResponse([] , 'success' , 200);
    }

    /**
     * @OA\Post(
     * path="/api/register/email",
     * summary="register by Email",
     * description="userregister by Email",
     * operationId="register email",
     * tags={"Auth"},
     * security={ {"bearer": {} }},
     *     @OA\Parameter(
     *          name="email",
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

    public function register_email(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
        ]);
        if ($validation->fails()) {
            return $this->errorResponse($validation->errors()->first() , 201);
        }
        $otp  = OTP::verify_otp_by_email($request->email);
        if(!$otp){
            return $this->errorResponse('Gagal saat mengirim email.' , 201);
        }
        
        return $this->successResponse([]);

    }

    /*
     * For Mobile Only
     * */

    /**
     * @OA\Post(
     * path="/api/token/get",
     * summary="token",
     * description="usertoken",
     * operationId="token",
     * tags={"Auth"},
     * security={ {"bearer": {} }},
     *       @OA\Parameter(
     *          name="email",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="password",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="device_name",
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
    public function get_token(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),201);
        }
        $agent = new Agent();
        $browser = $agent->browser();
        $platform = $agent->platform();

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken($request->device_name)->plainTextToken;
        if($token){
            UserActivity::create([
                'user_id'=> $user->id,
                'platform'=>$platform,
                'browser'=>$browser,
                'ip_address'=> $request->ip(),
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
            ]);
        }

        return $this->successResponse($token);
    }

    /*
     * For Mobile Only
     * */
    public function revoke_token(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse();
    }

    /*
     * For Website Only
     * */

    /**
     * @OA\Post(
     * path="/api/login",
     * summary="login",
     * description="UserActivity",
     * operationId="login",
     * tags={"Auth"},
     * security={ {"bearer": {} }},
     *     @OA\Parameter(
     *          name="email",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *    @OA\Parameter(
     *          name="password",
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
    public function login(Request $request)
    {
        $agent = new Agent();

        $today = date('Y-m-d');
        $validation = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),201);
        }

//        if(Auth::attempt($request->only('email','password'))){
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'status' => 'active'])){
            $data = [
                'data' => Auth::user()
            ];
            $user = UserActivity::where('user_id',Auth::id())->whereDate('created_at',$today)->first();
            if(!$user){
                DB::beginTransaction();
                try {
                    $user_login = UserActivity::create([
                        'user_id'   => Auth::id(),
                        'platform'  => $agent->platform(),
                        'browser'   =>$agent->browser(),
                        'ip_address'=>$request->ip(),
                        'utm_id' => trim($_COOKIE['utm_id']) == 'undefined' ? '' : trim($_COOKIE['utm_id']),
                        'utm_source' => trim($_COOKIE['utm_source']) == 'undefined' ? '' : trim($_COOKIE['utm_source']),
                        'utm_term' => trim($_COOKIE['utm_term']) == 'undefined' ? '' : trim($_COOKIE['utm_term']),
                        'utm_medium' => trim($_COOKIE['utm_medium']) == 'undefined' ? '' : trim($_COOKIE['utm_medium']),
                        'utm_campaign' => trim($_COOKIE['utm_campaign']) == 'undefined' ? '' : trim($_COOKIE['utm_campaign']),
                        'activity' => 'login',
                        'created_at'=>date('Y-m-d H:i:s'),
                    ]);
                    // Add DAU
                    Utils::add_dau(Auth::id());

                    DB::commit();

                } catch (\Exception $e) {
                    DB::rollback();
                }
            }
            return $this->successResponse($data);
        } 
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'status' => 'inactive'])){
            throw ValidationException::withMessages(
                [
                    'email' => ['Email anda telah dinonaktifkan.'],
                ]
            );
        }
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'status' => 'ban3'])){
            throw ValidationException::withMessages(
                [
                    'email' => ['Email anda terkena banned.'],
                ]
            );
        }
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'status' => 'ban7'])){
            throw ValidationException::withMessages(
                [
                    'email' => ['Email anda terkena banned.'],
                ]
            );
        }

        throw ValidationException::withMessages(
            [
                'email' => ['Periksa kembali email dan password!'],
            ]
        );
    }
    /**
     * @OA\Post(
     * path="/api/forget",
     * summary="forget",
     * description="forgetLogin",
     * operationId="forget",
     * tags={"Auth"},
     * security={ {"bearer": {} }},
     *     @OA\Parameter(
     *          name="email",
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
    public function forget(Request $request) {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors()->toArray()['email'][0],201);
        }

        $check = User::where('email', $request->email)->first();

        if ($check) {
            $otp = Utils::generate_code();
            $email = Emails::send_email($request->email, $request->email, 'Password Reset', $otp, Emails::$OTP_FORGET);
            if ($email) {
                PasswordReset::create([
                    'email' => $request->email,
                    'token' => $otp,
                    'created_at' => Carbon::now(),
                ]);
                return $this->successResponse(true);
            } else {
                return $this->errorResponse('Sorry, cannot reset password at this time', 500);
            }
        } else {
            throw ValidationException::withMessages(
                [
                    'email' => ['The provided credentials are incorrect.'],
                ]
            );
        }
    }

    /**
     * @OA\Post(
     * path="/api/forget/verify",
     * summary="forget verify otp",
     * description="forgetVerify",
     * operationId="forget verify",
     * tags={"Auth"},
     * security={ {"bearer": {} }},
     *     @OA\Parameter(
     *          name="email",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="otp",
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
    public function verify_forget_otp(Request $request) {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required',
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),201);
        }

        $verify = PasswordReset::where('email', $request->email)->where('token', $request->otp)->first();

        if ($verify) {
            // check OTP expiry
            $currentDate = strtotime($verify->created_at);
            $futureDate = $currentDate+(60*5);
            $expired = date("Y-m-d H:i:s", $futureDate);
            if (Carbon::now() > $expired) {
                return $this->errorResponse('Kode telah Expired', 401);
            }
            return $this->successResponse(true);
        } else {
            return $this->errorResponse('Kode yang dimasukkan salah.', 401);
        }
    }
    /**
     * @OA\Post(
     * path="/api/forget/set",
     * summary="forget set new password",
     * description="forget set new password",
     * operationId="forget set new password",
     * tags={"Auth"},
     * security={ {"bearer": {} }},
     *     @OA\Parameter(
     *          name="old_password",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="new_password",
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
    public function set_new_password(Request $request) {
        $validation = Validator::make($request->all(), [
            'email' => 'required',
            'new_password' => 'required',
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors(),201);
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $user->password = Hash::make($request->new_password);
            if ($user->save()) {
                return $this->successResponse(true);
            } else {
                return $this->errorResponse('Something went wrong.', 500);
            }
        } else {
            return $this->errorResponse('User not found.', 500);
        }
    }

    /**
     * @OA\Post(
     * path="/api/login/google",
     * summary="login google",
     * description="logingoogle",
     * operationId="logingoogle",
     * tags={"Auth"},
     * security={ {"bearer": {} }},
     *       @OA\Parameter(
     *          name="name",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="email",
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
    public function google(Request $request) {
        $user = User::where('email',$request->email)->first();
        $password = "12345678";
        $attempt = Auth::attempt(['email' => $request->email, 'password' => $password]);
        if (!$user) {
            $create = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($password),
                'registration_source' => 'google',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            if ($create) {
                Utils::add_nru($create->id);
                if($attempt){
                    $data = [
                        'data' => Auth::user()
                    ];
                    return $this->successResponse($data);
                }
            } else {
                throw ValidationException::withMessages(
                    [
                        'email' => ['Email is already taken.'],
                    ]
                );
            }
        } else {
            if($attempt){

                $data = [
                    'data' => Auth::user()
                ];
                return $this->successResponse($data);
            }
            $create = $user;
        }
        $token = $create->createToken('token-name')->plainTextToken;
        $response['auth'] = $token;
        return $this->successResponse($response);

    }

    public function facebook(Request $request) {
        $check = User::where('email', $request->Tt)->first();

        if ($check) {
            if(Auth::attempt($request->Tt)){
                $data = [
                    'data' => Auth::user()
                ];

                return $this->successResponse($data);
            }
        } else {
            $create = User::create([
                'name' => $request->Se,
                'email' => $request->Tt,
                'is_email_verified' => true,
                'registration_source' => 'facebook',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            if ($create) {
                Utils::add_nru($create->id);
                if (Auth::attempt($request->Tt)) {
                    $data = [
                        'data' => Auth::user()
                    ];

                    return $this->successResponse($data);
                }
            } else {
//                return $this->errorResponse('Email is alrady taken.', 409);
                throw ValidationException::withMessages(
                    [
                        'email' => ['Email is already taken.'],
                    ]
                );
            }
        }
    }

    /*
     * For Website Only
     * */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        return $this->successResponse();
    }

    /**
     * @OA\Post (
     * path="/api/auth/send/otp",
     * summary="Auth",
     * description="send sms",
     * operationId="send sms",
     * tags={"Auth"},
     * security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="email",
     *          required=false,
     *          in="query",
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
     * @OA\Response(
     *    response=200,
     *    description="Successful Operation",
     *      @OA\JsonContent(
     *        )
     *     )
     * )
     */
    public function send_otp(Request $request){
        $has_code = substr($request->form, 0, 2) == '62' ? true : false;
        $number = $has_code ? ('0'.substr($request->form, 2)) : $request->form;
        
        $phone_number = User::where('phone' , $number)->first();
        if($phone_number){
            return $this->errorResponse('Nomor telepon telah dipakai.',201);
        }
        if($request->type == 'phone'){
            if(!Auth::check()) return ;
            $email = Auth::user()->email;
            $otp = OTP::otp_by_phone($email , $number);
        }
        if($request->type == 'email'){
            $otp = OTP::otp_by_email($number);
        }
        
        if(!$otp[0]){
            return $this->errorResponse( $request->type == 'email' ? static::OTP_FAILED_SEND_EMAIL : static::OTP_FAILED_SEND_PHONE, $otp[1]);
        }
        return  $this->successResponse([]);
    }

    /**
     * @OA\Post (
     * path="/api/auth/verify/otp",
     * summary="Auth",
     * description="verify sms otp",
     * operationId="verify sms otp",
     * tags={"Auth"},
     * security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *          name="email",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="phone_number",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="otp_code",
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
    public function verify_otp(Request $request){

        $validation = Validator::make($request->all(), [
            'form' => 'required',
            'otp' => 'required',
            'type' => 'required'
        ]);

        if($validation->fails()){
            return $this->errorResponse($validation->errors()->first() , 201);
        }

        if($request->type == 'phone'){
            if(!Auth::check()) return ;
        }
        $now = date('Y-m-d H:i:s');
        $form =  $request->form;
        $otp =  $request->otp;

        if($request->type == 'email'){
            $otp  = OtpCode::where('email' , $request->form)->where('code',$otp)->orderBy('id' , 'DESC')->first();
            $email = $request->form;
        }
        if($request->type == 'phone'){
            //print_r(Auth::user());
            $email =  Auth::user()->email;
            $otp  = OtpCode::where('email' , $email)->where('code',$otp)->orderBy('id' , 'DESC')->first();
            $telephones = User::where('phone',$form)->first();
            if($telephones){
                if($telephones->email != $email){
                    return  $this->errorResponse(static::PHONE_HAS_VERIFY,static::PHONE_HAS_VERIFY_CODE);
                }
            }
        }

        if(!$otp){
            return  $this->errorResponse(static::OTP_WRONG,static::ERROR_OTP_WRONG_CODE);
        }
        
        if(strtotime($otp) >= strtotime(date('Y-m-d H:i:s'). ' + 5 minute')){
            return  $this->errorResponse(static::OTP_EXPIRED,static::ERROR_OTP_WRONG_CODE);
        }
        $user = User::where('email' , $email)->where('is_vendor' , false)->first();

        if($request->type == 'email'){
            $user->is_email_verified = true;
        }
        if($request->type == 'phone'){
            $user->phone = $form;
            $user->is_phone_verified = true;
        }

        if(!$user->save()){
            return  $this->errorResponse(static::ERROR_WHEN_SAVE_DATA,static::ERROR_WHEN_SAVE_DATA_CODE);
        }
        return $this->successResponse();
    }


    public function phone_check(Request $request){
        if(!Auth::check()){
            return $this->errorResponse('Kamu harus login terlebih dahulu' , 400);
        }

        if($request->phone == ''){
            return $this->errorResponse('Nomor Telepon tidak boleh kosong' , 400);
        }

        if(User::where('phone' , $request->phone)->first()){
            return $this->errorResponse('Nomor Telepon Sudah didaftarkan' , 400);
        }

        return $this->successResponse([]);

    }

    public function register_verify_otp(Request $request){
        $validation = Validator::make($request->all(), [
            'form' => 'required',
            'otp' => 'required',
            'type' => 'required'
        ]);
        if($validation->fails()){
            return $this->errorResponse($validation->errors()->first() , 201);
        }
        $otp  = OtpCode::where('email' , $request->form)->where('code',$request->otp)->orderBy('id' , 'DESC')->first();
        if(!$otp){
            return  $this->errorResponse(static::OTP_WRONG,static::ERROR_OTP_WRONG_CODE);
        }
        $currentDate = strtotime($otp->created_at);
        $futureDate = $currentDate+(60*5);
        $expired = date("Y-m-d H:i:s", $futureDate);
        
        if(Carbon::now() > $expired){
            return  $this->errorResponse(static::OTP_EXPIRED,static::ERROR_OTP_WRONG_CODE);
        }
        return $this->successResponse([]);
    }

    public function add_dau(){
        $user = Auth::user();
        if($user){
            Utils::add_dau($user->id);
        }
        return $this->successResponse([]);
    }
}
