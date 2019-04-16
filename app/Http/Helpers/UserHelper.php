<?php
/**
 * Created by PhpStorm.
 * User: shockbyte
 * Date: 4/6/2019
 * Time: 12:37 PM
 */

namespace App\Http\Helpers;


use \Illuminate\Http\Request;


use App\Http\Helpers\CryptoHelper;
use App\Http\Helpers\MailHelper;

use App\Models\User;
use App\Models\UserSettings;
use App\Models\Balance;
use App\Models\EmailConfirm;

class UserHelper
{
    /**
     * @param string $email
     * @param string $password
     * @return array
     */
    public static function GetUserData($email, $password){
        $user_data = [
            'email' => @$email,
            'password' => @$password,
            'ip' => $_SERVER['REMOTE_ADDR'],
            'salt' => hash("sha256", base64_encode(openssl_random_pseudo_bytes(64)))
        ];

        return $user_data;
    }


    /**
     * @param Request $request
     * @param bool $secure
     * @param bool $return_user
     * @return int|User
     */
    public static function CheckAuth(Request $request, $return_user = false){
        $user_session = @$_COOKIE['user_session'];
        if(!$user_session)
            $user_session = @$request->session()->get('user_session');

        if(!$user_session)
            return 1;

        $user_session = @CryptoHelper::DecryptResponse($user_session);

        $user_session = json_decode($user_session);
        if(!$user_session)
            return 2;

        if($user_session->ip != $_SERVER['REMOTE_ADDR'])
            return 2;

        $user = User::where('email', @$user_session->email)->where('password', @$user_session->password)->get()->first();
        if(!$user)
            return 2;

        return $return_user ? $user : 0;
    }

    /**
     * @param $email
     * @param $password
     * @param $referral
     * @return bool
     */
    public static function CreateNewUser($email, $password, $referral){
        if(@User::where('email', $email)->get()->first())
            return false;

        $user = new User();
        $user_settings = new UserSettings();
        $user_balance = new Balance();

        $user->email = $email;
        $user->password = hash("sha256", $password);
        $user->referral_code = strtoupper(hash("sha256", openssl_random_pseudo_bytes(64)));
        $user->save();

        $user_settings->status = 0;
        $user_settings->reg_date = date("Y-m-d H:i:s");

        $ref_id = @User::where('referral_code', $referral)->get()->first()->id;
        if($ref_id)
            $user_settings->referral = $ref_id;
        $user_settings->user_id = $user->id;
        $user_settings->save();

        $user_balance->balance = 0;
        $user_balance->total_spend = 0;
        $user_balance->user_id = $user->id;
        $user_balance->save();

        $data = [
            'link' => url('/email/confirm/').MailHelper::NewMailConfirmToken($user->id),
            'mail_title' => 'Регистрация на сайте ALPHA CHEAT'
        ];

        MailHelper::SendMail('mail.types.reg_complete', $data, $user->email, 'Подтверждение регистрации :: '.url('/'));
        return true;
    }

    public static function CheckSteamNick($steam_id){
        $user_data = simplexml_load_file("http://steamcommunity.com/profiles/$steam_id?xml=1", null, LIBXML_NOCDATA);
        return strpos(@$user_data->steamID, 'alphacheat.com') !== false;
    }
}