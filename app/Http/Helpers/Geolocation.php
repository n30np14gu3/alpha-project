<?php
namespace App\Http\Helpers;


class Geolocation
{
    public static function getLocationInfo(){
        $ip = "";
        if(env('APP_ENV') != 'local'){
            $client  = @$_SERVER['HTTP_CLIENT_IP'];
            $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
            $remote  = @$_SERVER['REMOTE_ADDR'];
            if(filter_var($client, FILTER_VALIDATE_IP)){
                $ip = $client;
            }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
                $ip = $forward;
            }else{
                $ip = $remote;
            }
        }
        return @file_get_contents("http://www.geoplugin.net/json.gp?ip=$ip");
    }
}