<?php
namespace App\Http\Helpers;


use Illuminate\Http\Request;

use App\Http\Requests;

class CryptoHelper
{

    public static function LEFT_SHIFT($z_value, $z_shift){
        return (($z_value << $z_shift) | ($z_value >> (8 - $z_shift))) & 0xFF;
    }

    public static function RIGHT_SHIFT($z_value, $z_shift){
        return (($z_value >> $z_shift) | ($z_value << (8 - $z_shift))) & 0xFF;
    }

    public  static  function strToHex($string, $size){
        $hex='';
        for ($i=0; $i < $size; $i++){
            $hex .= dechex(ord($string[$i]));
        }
        return $hex;
    }

    public static function hexToStr($hex){
        $string='';
        for ($i=0; $i < strlen($hex)-1; $i+=2){
            $string .= chr(hexdec($hex[$i].$hex[$i+1]));
        }
        return $string;
    }

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