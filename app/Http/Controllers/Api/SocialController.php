<?php

namespace App\Http\Controllers\Api;

use App\Dau;
use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\MediaLog;
use App\Nru;
use App\Provider;
use App\User;
use App\UserActivity;
use Carbon\Carbon;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Jenssegers\Agent\Agent;


class SocialController extends Api
{
    public function redirectToProvider($provider)
    {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }
        return Socialite::driver($provider)->redirect();

    }

    public function redirectToProviderGoogle($provider)
    {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }
        return Socialite::driver($provider)->redirect();

    }

    public function handleProviderCallback($provider, Request $request){

        $now = Carbon::now();
        $today =  $now->toDateString();

        try{
            $user = Socialite::driver($provider)->stateless()->user();

        }catch (ClientException $exception){
            return $this->errorResponse(static::INVALID_SOCIAL_PROVIDER,$exception->getMessage());
        };
        //MediaLog::create(['log' =>  json_encode($user),  'created_at'=>date('Y-m-d H:i:s')]);

        $log = User::where('email', $user->email)->first();
        $authUser = $this->findOrCreateUser($user,$provider);
        //MediaLog::create(['log' =>  json_encode($authUser),  'created_at'=>date('Y-m-d H:i:s')]);
        $log = UserActivity::where('user_id',$authUser->id)->whereDate('created_at',$today)->first();
       
        $log_activity = UserActivity::where('user_id',$authUser->id)->where('activity', 'login')->whereDate('created_at',$today)->first();
        
        $agent = new Agent();

        $browser = $agent->browser();
        $platform = $agent->platform();
        if(!$log){
            Utils::add_nru($authUser->id);
            $activity = 'register';

        } else{
            $activity = 'login';
        }
        // Add DAU
        Utils::add_dau($authUser->id);

        DB::beginTransaction();
        try {
            $user_login = UserActivity::create([
                'user_id'=> $authUser->id,
                'platform'=> $platform,
                'browser'=>$browser,
                'ip_address'=>$request->ip(),
                'utm_id' => trim($_COOKIE['utm_id']) == 'undefined' ? '' : trim($_COOKIE['utm_id']),
                'utm_source' => trim($_COOKIE['utm_source']) == 'undefined' ? '' : trim($_COOKIE['utm_source']),
                'utm_term' => trim($_COOKIE['utm_term']) == 'undefined' ? '' : trim($_COOKIE['utm_term']),
                'utm_medium' => trim($_COOKIE['utm_medium']) == 'undefined' ? '' : trim($_COOKIE['utm_medium']),
                'utm_campaign' => trim($_COOKIE['utm_campaign']) == 'undefined' ? '' : trim($_COOKIE['utm_campaign']),
                'activity'=> $activity,
                'created_at'=>date('Y-m-d H:i:s'),
            ]);
            DB::commit();

        } catch (\Exception $e) {
            MediaLog::create(['log' => $e->getMessage() ,  'created_at'=>date('Y-m-d H:i:s')]);
            DB::rollback();
        }

        Cache::add($request->code, $user, 3600);
        Auth::login($authUser, true);

    }

    protected function validateProvider($provider){
        if(!in_array($provider,['facebook','google'])){
            return $this->errorResponse(static::LOGIN_SOCIAL_FAILED,static::LOGIN_SOCIAL_CODE);
        }
    }

    private function findOrCreateUser($user,$provider){

        $user_created = User::firstOrCreate(
        [
            'email'=>$user->email
        ],
        [
            'email_verified_at'=>Utils::now(),
            'is_email_verified' => true,
            'name'=>$user->name,
            'image' => $user->avatar,
            'registration_source'=>$provider,
            'status'=> 'active',
        ]
        );

        $user_created->provider()->updateOrCreate([
            'provider'=>$provider,
            'provider_id'=>$user->id,
        ],
            [
                'avatar'=>$user->avatar
            ]
        );
        $users = User::where('email' , $user->email)->first();

        return $users;
    }

    public function login($provider, Request $request)
    {
        try{
            $user = Cache::get($request->code);

        }catch (ClientException $exception){
            return $this->errorResponse(static::INVALID_SOCIAL_PROVIDER,$exception->getMessage());
        };

        $authUser = $this->findOrCreateUser($user,$provider);
        Auth::login($authUser, true);
        Cache::forget($request->code);

        return response()->json($authUser);
    }
}
