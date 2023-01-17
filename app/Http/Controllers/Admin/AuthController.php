<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Helpers\Emails;
use App\Helpers\OTP;
use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\PasswordReset;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    protected $maxAttempts = 3;
    protected $decayMinutes = 2;


    public function login(){
        if (Auth::check()){
            Auth::logout();
        }
        return view('admin.login');
    }

    public function check_login(Request $request){
        $validation = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> $validation->errors()]);
        }

        $my_ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $request->ip();//$request->ip();
        $required_ip = array('172.31.33.178','110.137.193.197','127.0.0.1','36.91.162.242');
        Session::put('email_second_verification__', $request->email);
        if (auth()->guard('admin')->attempt($request->only('email', 'password'))) {
            //$request->session()->regenerate();
            if (in_array($my_ip, $required_ip)){
                $request->session()->regenerate();
                $this->clearLoginAttempts($request);
                return json_encode(['status'=> true, 'message'=> "Success"]);
            } else {
                Auth::guard('admin')->logout();
                $otp = OTP::otp_number();
                $email = Emails::send_email($request->email, $request->email, 'Login Verification', 'Your OTP verification code '.$otp);
                if ($email) {
                    PasswordReset::create([
                        'email' => $request->email,
                        'token' => $otp,
                        'created_at' => Carbon::now(),
                    ]);
                } 
                return json_encode(['status'=> 'verif-otp', 'message'=> "Success"]);

            }
            Auth::guard('admin')->logout();
        }

        return json_encode(['status' => false, 'message' => 'Wrong credentials']);
    }

    public function admin_logout()
    {
        auth()->guard('admin')->logout();
        session()->flush();

        return redirect()->route('admin.login');
    }

    public function verification()
    {
        return view('admin.login-verification');
    }

    public function check_verification(Request $request) {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->errors()->toArray()['email'][0],201);
        }

        $check = Admin::where('email', $request->email)->first();
        if($check != Session::get('email_second_verification__')){
            throw ValidationException::withMessages(
                [
                    'email' => ['The provided credentials are incorrect.'],
                ]
            );
        }
        if ($check) {
            $otp = Utils::generate_code();
            $email = Emails::send_email($request->email, $request->email, 'Login with other IP', $otp, Emails::$OTP_FORGET);
            if ($email) {
                PasswordReset::create([
                    'email' => $request->email,
                    'token' => $otp,
                    'created_at' => Carbon::now(),
                ]);
                return json_encode(['status'=> true, 'message'=> "Success"]);
            } else {
                return $this->errorResponse('Sorry, cannot login at this time', 500);
            }
        } else {
            throw ValidationException::withMessages(
                [
                    'email' => ['The provided credentials are incorrect.'],
                ]
            );
        }
    }

    public function check_otp_view()
    {
        return view('admin.verification');
    }

    public function check_otp(Request $request) {

        $validation = Validator::make($request->all(), [
            'otp' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validation->fails()) {
            return json_encode(['status'=> false, 'message'=> "Wrong credentials"]);
        }
        $email = Session::get('email_second_verification__');
        $verify = PasswordReset::where('email', $email)->where('token', $request->otp)->first();
        if ($verify) {
            // check OTP expiry
            $currentDate = strtotime($verify->created_at);
            $futureDate = $currentDate+(60*5);
            $expired = date("Y-m-d H:i:s", $futureDate);
            if (Carbon::now() > $expired) {
                return json_encode(['status'=> false, 'message'=> "Kode telah Expired"]);
            }
            if (auth()->guard('admin')->attempt($request->only('email', 'password'))) {
                $this->clearLoginAttempts($request);
            }

            if (auth()->guard('admin')->attempt($request->only('email','password'))) {
                $request->session()->regenerate();
                $this->clearLoginAttempts($request);

            }
            return json_encode(['status'=> true, 'message'=> "Success"]);

        } else {
            return json_encode(['status'=> false, 'message'=> "Kode yang dimasukkan salah"]);
        }
    }
}
