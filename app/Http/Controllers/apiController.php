<?php

namespace App\Http\Controllers;

use App\Http\Helpers\CryptoHelper;
use App\Models\ApiRequest;
use App\Models\Ban;
use App\Models\GameModule;
use App\Models\Subscription;
use App\Models\SubscriptionSettings;
use App\Models\User;
use App\Models\UserSettings;
use Illuminate\Http\Request;

use App\Http\Requests;

class apiController extends Controller
{
    public function login(){
        $response = [
            'code' => env('API_CODE_UNKNOWN_ERROR'),
            'data' => null
        ];

        $email = @$_POST['email'];
        $password = @$_POST['password'];
        $hwid = @$_POST['hwid'];
        $user_ip = $_SERVER['REMOTE_ADDR'];
        $current_time = time();

        $user = @User::where('email', $email)->where('password', $password)->get()->first();
        if(!$user){
            $response['code'] = env('API_CODE_USER_NOT_FOUND');
            return json_encode($response);
        }

        $user_bans = @Ban::where('user_id', $user->id)->get()->first();
        $user_settings = UserSettings::where('user_id', $user->id)->get()->first();
        if($user_bans || !$user_settings->status){
            $response['code'] = env('API_CODE_USER_BLOCKED');
            return json_encode($response);
        }

        if($user->hwid && $user->hwid != $hwid){
            $response['code'] = env('API_CODE_HWID_ERROR');
            return json_encode($response);
        }

        if(!$user->hwid){
            $user->hwid = $hwid;
        }

        $user_subscription = @Subscription::where('user_id', $user->id)->get()->first();
        if(!$user_subscription){
            $response['code'] = env('API_CODE_SUBSCRIPTION_EXPIRY');
            return json_encode($response);
        }

        $subscription_modules = @SubscriptionSettings::where('subscription_id', $user_subscription->id)->where('end_date', '>', $current_time)->get();
        if(!count($subscription_modules)){
            $response['code'] = env('API_CODE_SUBSCRIPTION_EXPIRY');
            return json_encode($response);
        }

        $response['code'] = env('API_CODE_OK');
        $response['data'] = [
            'nickname' => $user_settings->nickname ? $user_settings->nickname : 'NONAME',
            'user_id' => $user->id,
            'access_token' => '',
            'subscription_modules' => []
        ];

        foreach($subscription_modules as $module){
            $subscription_module = [
              'name' => '',
              'end_date' => ''
            ];

            $subscription_module['name'] = GameModule::where('id', $module->module_id)->get()->first()->name;
            $subscription_module['end_date'] = date("m-d-Y H:i:s", $module->end_date);
            array_push($response['data']['subscription_modules'], $subscription_module);
        }

        $api_request = new ApiRequest();
        $api_request->user_id = $user->id;
        $api_request->session_ip = $user_ip;
        $api_request->session_time = $current_time;
        $api_request->token = hash("sha256", base64_encode(openssl_random_pseudo_bytes(64)).time());
        $api_request->save();

        $response['data']['access_token'] = $api_request->token;

        return json_encode($response);
    }

    public function requestSession(){

    }
}
