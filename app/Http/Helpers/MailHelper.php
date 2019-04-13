<?php
/**
 * Created by PhpStorm.
 * User: shockbyte
 * Date: 4/7/2019
 * Time: 5:56 PM
 */

namespace App\Http\Helpers;


use App\Http\Helpers\UserHelper;
use Illuminate\Http\Request;


use App\Models\User;
use App\Models\LoginHistory;

use App\Http\Helpers\CryptoHelper;
use App\Http\Helpers\Geolocation;

class MailHelper
{
    public static function SendMail($view, $data, $email, $title){
        Mail::send($view, $data, function ($message) use ($email, $title){
            $message->from(env('MAIL_USERNAME'), 'ALPHA PROJECT NOREPLY');
            $message->to($email)->subject($title);
        });
    }
}