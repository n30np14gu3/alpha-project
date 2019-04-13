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
     * @return bool
     */
    public static function CheckAuth(Request $request, $secure = false){
        $user_session = @$_COOKIE['user_session'];
        if(!$user_session)
            $user_session = @$request->session()->get('user_session');

        if(!$user_session && !$secure)
            return true;

        $user_session = @CryptoHelper::DecryptResponse($user_session);

        $user_session = json_decode($user_session);
        if(!$user_session)
            return false;


        if($user_session['ip'] != $_SERVER['REMOTE_ADDR'])
            return false;

        $user = User::where('email', @$user_session['email'])->where('password', @$user_session['password'])->get()->first();
        if(!$user)
            return false;

        return true;
    }

    public static function CreateNewUser($email, $password, $referral){
        if(@User::where('email', $email)->get()->first())
            return false;

        $user = new User();
        $user_settings = new UserSettings();
        $user_balance = new Balance();

        $user->email = $email;
        $user->password = hash("sha256", $password);
        $user->status = 0;
        $user->reg_date = date("Y-m-d H:i:s");
        $user->referral_code = strtoupper(hash("sha256", openssl_random_pseudo_bytes(64)));
        $user->save();

        $user_settings->referral = $referral;
        $user_settings->user_id = $user->id;
        $user_settings->save();

        $user_balance->balance = 0;
        $user_balance->total_spend = 0;
        $user_balance->user_id = $user->id;
        $user_balance->save();

        $confirm_data = new EmailConfirm();
        $confirm_data->user_id = $user->id;
        $confirm_data->ip = $_SERVER['REMOTE_ADDR'];
        $confirm_data->request_time = time();
        $confirm_data->code = strtoupper(hash("sha256", openssl_random_pseudo_bytes(64)));
        $confirm_data->visited = 0;
        $confirm_data->save();

        $data = [
          'link' => url('/')."/email/confirm/".$confirm_data->code
        ];
        MailHelper::SendMail('mail.types.reg_complete', $data, $user->email, 'Подтверждение регистрации :: '.url());
        return true;
    }
}