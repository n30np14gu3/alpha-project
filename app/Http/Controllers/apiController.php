<?php

namespace App\Http\Controllers;

use App\Http\Helpers\CryptoHelper;
use App\Http\Helpers\UserHelper;
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

        if(!$email || !$password || !$hwid){
            http_response_code(403);
            die();
        }

        $user_ip = $_SERVER['REMOTE_ADDR'];
        $current_time = time();

        $user = @User::where('email', $email)->where('password', $password)->get()->first();
        if(!$user){
            $response['code'] = env('API_CODE_USER_NOT_FOUND');
            return CryptoHelper::EncryptResponse(json_encode($response), env('CRYPTO_KEY_API'), env('CRYPTO_IV_API'));
        }

        $user_settings = UserSettings::where('user_id', $user->id)->get()->first();
        if(!UserHelper::CheckUserActivity($user)){
            $response['code'] = env('API_CODE_USER_BLOCKED');
            return CryptoHelper::EncryptResponse(json_encode($response), env('CRYPTO_KEY_API'), env('CRYPTO_IV_API'));
        }


        $user_subscription = @Subscription::where('user_id', $user->id)->get()->first();
        if(!$user_subscription){
            $response['code'] = env('API_CODE_SUBSCRIPTION_EXPIRY');
            return CryptoHelper::EncryptResponse(json_encode($response), env('CRYPTO_KEY_API'), env('CRYPTO_IV_API'));
        }

        if($user_subscription->hwid && $user_subscription->hwid != $hwid){
            $response['code'] = env('API_CODE_HWID_ERROR');
            return CryptoHelper::EncryptResponse(json_encode($response), env('CRYPTO_KEY_API'), env('CRYPTO_IV_API'));
        }

        if(!$user_subscription->hwid){
            if(count(Subscription::where('game_id', $user_subscription->game_id)->where('hwid', $hwid)->get()) >= 1){
                $response['code'] = env('API_CODE_SUBSCRIPTION_DUPLICATE');
                return CryptoHelper::EncryptResponse(json_encode($response), env('CRYPTO_KEY_API'), env('CRYPTO_IV_API'));
            }
            $user_subscription->hwid = $hwid;
        }

        $subscription_modules = @SubscriptionSettings::where('subscription_id', $user_subscription->id)->where('end_date', '>', $current_time)->get();
        if(!count($subscription_modules)){
            $response['code'] = env('API_CODE_SUBSCRIPTION_EXPIRY');
            return CryptoHelper::EncryptResponse(json_encode($response), env('CRYPTO_KEY_API'), env('CRYPTO_IV_API'));
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

        $user_subscription->activation_date = date("Y-m-d H:i:s");
        $user_subscription->save();

        $api_request = new ApiRequest();
        $api_request->user_id = $user->id;
        $api_request->session_ip = $user_ip;
        $api_request->session_time = $current_time;
        $api_request->token = hash("sha256", base64_encode(openssl_random_pseudo_bytes(64)).time());
        $api_request->save();

        $response['data']['access_token'] = $api_request->token;

        return CryptoHelper::EncryptResponse(json_encode($response), env('CRYPTO_KEY_API'), env('CRYPTO_IV_API'));
    }

    public function requestSession(){

    }
}
