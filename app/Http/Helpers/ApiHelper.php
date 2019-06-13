<?php

namespace App\Http\Helpers;


use App\Models\ApiRequest;
use App\Models\Subscription;
use App\Models\SubscriptionSettings;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ApiHelper
{
    public static function CheckToken($token, $game_id = 0){
        $ip = $_SERVER['REMOTE_ADDR'];
        $current_time = time();

        $api_request = ApiRequest::where('token', $token)->get()->first();
        if(!$api_request)
            return false;

        if($api_request->session_ip != $ip)
            return false;

        if($current_time - $api_request->session_time > 60*60*24)
            return false;

        $user = @User::where('id', $api_request->user_id)->get()->first();
        if(!UserHelper::CheckUserActivity($user))
            return false;

        if($game_id){
            $user_subscription = @Subscription::where('user_id', $user->id)->where('game_id', $game_id)->get()->first();
            if(!$user_subscription){
                return false;
            }

            $subscription_modules = @SubscriptionSettings::where('subscription_id', $user_subscription->id)->where('end_date', '>', $current_time)->get();
            if(!count($subscription_modules)){
                return false;
            }
        }

        return true;
    }

    public static function SaveKey($ip){
        $config = [
            'last_update' => time(),
            'aes_key' => UserHelper::NewPassword(32),
            'aes_iv' => UserHelper::NewPassword(16)
        ];
        Storage::put("/keys/$ip/config.json", json_encode($config));

        return [$config['aes_key'], $config['aes_iv']];
    }

    public static function CheckKey($ip){
        $key_info = (array)json_decode(Storage::get("/keys/$ip/config.json"));
        if(!$key_info['last_update'])
            return self::SaveKey($ip);

        if((time() - $key_info['last_update']) > 60 * 60 * 2)
            return self::SaveKey($ip);

        return [$key_info['aes_key'], $key_info['aes_iv']];
    }
}