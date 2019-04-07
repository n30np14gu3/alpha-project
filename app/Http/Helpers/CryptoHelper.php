<?php
namespace App\Http\Helpers;


use Illuminate\Http\Request;

use App\Http\Requests;

class CryptoHelper
{
    /**
     * return base64 str
     * @param $rsp
     * @return string
     */
    public static function EncryptResponse($rsp){
        return base64_encode(openssl_encrypt($rsp,"aes-256-cbc", env('CRYPTO_KEY'), OPENSSL_RAW_DATA, env('CRYPTO_IV')));
    }

    /**
     * param as base64 string
     * @param $rsp
     * @return string
     */
    public static function DecryptResponse($rsp){
        return openssl_decrypt(base64_decode($rsp),"aes-256-cbc", env('CRYPTO_KEY'), OPENSSL_RAW_DATA, env('CRYPTO_IV'));
    }
}