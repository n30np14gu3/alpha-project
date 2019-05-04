<?php
namespace App\Http\Helpers;


use Illuminate\Http\Request;

use App\Http\Requests;

class CryptoHelper
{
    /**
     * return base64 str
     * @param $rsp
     * @param null $key
     * @param null $iv
     * @return string
     */
    public static function EncryptResponse($rsp, $key = null, $iv = null){
        if(!$key || !$iv){
            $key = env('CRYPTO_KEY');
            $iv = env('CRYPTO_IV');
        }
        return base64_encode(openssl_encrypt($rsp,"aes-256-cbc", $key, OPENSSL_RAW_DATA, $iv));
    }

    /**
     * param as base64 string
     * @param $rsp
     * @param null $key
     * @param null $iv
     * @return string
     */
    public static function DecryptResponse($rsp, $key = null, $iv = null){
        if(!$key || !$iv){
            $key = env('CRYPTO_KEY');
            $iv = env('CRYPTO_IV');
        }
        return openssl_decrypt(base64_decode($rsp),"aes-256-cbc", $key, OPENSSL_RAW_DATA, $iv);
    }

}