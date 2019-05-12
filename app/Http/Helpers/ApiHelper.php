<?php

namespace App\Http\Helpers;


use App\Models\ApiRequest;
use App\Models\Subscription;
use App\Models\SubscriptionSettings;
use App\Models\User;

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
}