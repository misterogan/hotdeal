<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Utils;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Jenssegers\Agent\Agent;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\User;

class SocialiteController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
		
        if (Auth::check()) {
            return redirect('/home');
        }

        $oauthUser = Socialite::driver('google')->user(); 
        $user = User::where('email', $oauthUser->email)->first(); 
		if( is_object($user) ){
			if($user->status != 'active'){
				return redirect('/blocked/'.$user->status);
			}
		}
		
        if ($user) {
            $user->last_login = Carbon::now()->toDateTimeString();
            $user->register_type = 'google';
            $user->save();
            Auth::loginUsingId($user->id);
            return redirect('/home');
        } else {
            $splitName = explode(' ', $oauthUser->name);
            $first_name = $splitName[0];

            $last_name = str_replace($first_name,'', $oauthUser->name);

            $agent = new Agent(); 
            $newUser = User::create([
                'first_name' => $first_name,
                'last_name'=> $last_name,
                'email' => trim($oauthUser->email),
                'google_id'=> $oauthUser->id,
                'password' => md5($oauthUser->token),
                'img_profile'=>$oauthUser->avatar_original != '' ? $oauthUser->avatar_original : '/assets/profile/AvatarMale.png',
                'status' => 'active',
                'email_verified_at' => date('Y-m-d h:i:s'),
                'is_email_verified' => true,
                'slug' => Utils::check_slug($oauthUser->name, 'users'),
                'register_type' => 'google',
                'language' => App::getLocale(),
                'last_login' => Carbon::now()->toDateTimeString(),
                'last_ip' => $request->getClientIp(),
                'device'=> $agent->deviceType(),
                'os' => $agent->platform(),
                'browser_name' => $agent->browser()
            ]); 

            Utils::add_nru($newUser->id);
			
            Auth::login($newUser);
            $newUser->markEmailAsVerified();

            return redirect('/survey');
        }
    }
	
    public function handleFacebookCallback(Request $request)
    {
        if (Auth::check()) {
            return redirect('/home');
        }
        $oauthUser = Socialite::driver('facebook')->user();
        $user = User::where('email', $oauthUser->email)->first();
        if($user->status != 'active'){
            return redirect('/blocked/'.$user->status);
        }
        if ($user) {
            $user->last_login = Carbon::now()->toDateTimeString();
            $user->register_type = 'facebook';
            $user->save();
            Auth::loginUsingId($user->id);
            return redirect('/home');
        } else {
            $splitName = explode(' ', $oauthUser->name);
            $first_name = $splitName[0];

            $last_name = str_replace($first_name,'', $oauthUser->name);

            $agent = new Agent();

            $newUser = User::create([
                'first_name' => $first_name,
                'last_name'=> $last_name,
                'email' => $oauthUser->email,
                'facebook_id'=> $oauthUser->id,
                'password' => md5($oauthUser->token),
                'img_profile'=>$oauthUser->avatar_original != '' ? $oauthUser->avatar_original : '/assets/profile/AvatarMale.png',
                'status' => 'active',
                'email_verified_at' => date('Y-m-d h:i:s'),
                'is_email_verified' => true,
                'slug' => Utils::check_slug($oauthUser->name, 'users'),
                'register_type' => 'google',
                'language' => App::getLocale(),
                'last_login' => Carbon::now()->toDateTimeString(),
                'last_ip' => $request->getClientIp(),
                'device'=> $agent->deviceType(),
                'os' => $agent->platform(),
                'browser_name' => $agent->browser()
            ]);

            Utils::add_nru($newUser->id);

            Auth::login($newUser);

            $newUser->markEmailAsVerified();

            return redirect('/survey');
        }
    }
}
