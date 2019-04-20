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
use Illuminate\Support\Facades\Mail;


use App\Models\User;
use App\Models\LoginHistory;

use App\Http\Helpers\CryptoHelper;
use App\Http\Helpers\Geolocation;

use App\Models\EmailConfirm;
use App\Models\PasswordRecovery;

class MailHelper
{
    public static function SendMail($view, $data, $email, $title){
        try{
            Mail::send($view, $data, function ($message) use ($email, $title){
                $message->from(env('MAIL_USERNAME'), 'ALPHA PROJECT NOREPLY SERVICE');
                $message->to($email)->subject($title);
            });
        }catch (\Swift_TransportException $ex){

        }
    }

    /**
     * @param int $user_id
     * @return string
     */
    public static function NewMailConfirmToken($user_id){
        $confirm_data = new EmailConfirm();
        $confirm_data->user_id = $user_id;
        $confirm_data->ip = $_SERVER['REMOTE_ADDR'];
        $confirm_data->request_time = time();
        $confirm_data->code = strtoupper(hash("sha256", openssl_random_pseudo_bytes(64)));
        $confirm_data->visited = 0;
        $confirm_data->save();

        return $confirm_data->code;
    }

    public static function NewPasswordRecoveryToken($user_id){
        $password_recovery = new PasswordRecovery();
        $password_recovery->user_id = $user_id;
        $password_recovery->request_time = time();
        $password_recovery->visited = 0;
        $password_recovery->code = strtoupper(hash("sha256", openssl_random_pseudo_bytes(64)));
        $password_recovery->ip = $_SERVER['REMOTE_ADDR'];
        $password_recovery->save();

        return $password_recovery->code;
    }
}