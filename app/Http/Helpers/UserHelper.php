<?php
/**
 * Created by PhpStorm.
 * User: shockbyte
 * Date: 4/6/2019
 * Time: 12:37 PM
 */

namespace App\Http\Helpers;


use App\Models\Ban;
use App\Models\PaymentHistory;
use App\Models\SubscriptionSettings;
use App\Models\UserInvoice;
use \Illuminate\Http\Request;


use App\Http\Helpers\CryptoHelper;
use App\Http\Helpers\MailHelper;

use App\Models\User;
use App\Models\UserSettings;
use App\Models\Balance;

class UserHelper
{
    /**
     * @param string $email
     * @param string $password
     * @return array
     */
    public static function CreateUserArray($id, $email, $password){
        $user_data = [
            'email' => @$email,
            'password' => @$password,
            'ip' => $_SERVER['REMOTE_ADDR'],
            'salt' => hash("sha256", base64_encode(openssl_random_pseudo_bytes(64))),
            'id' => $id
        ];

        return $user_data;
    }

    /**
     * @param Request $request
     * @return array
     */
    public static function GetLocalUserInfo(Request $request){
        $user_session = @$_COOKIE['user_session'];
        if(!$user_session)
            $user_session = @$request->session()->get('user_session');

        return (array)json_decode(@CryptoHelper::DecryptResponse($user_session));
    }

    /**
     * @param Request $request
     * @param string %newPassword
     */
    public static function UpdateUserPassword(Request $request, $newPassword){
        $user_data = self::GetLocalUserInfo($request);
        $user = User::where('id', $user_data['id'])->get()->first();
        $user->password = hash("sha256", $newPassword);
        $user_data['password'] = $user->password;
        $user_data = CryptoHelper::EncryptResponse(json_encode($user_data));
        $request->session()->put('user_session', $user_data);
        if(@$_COOKIE['user_session'])
            setcookie('user_session', $user_data, time() + 60*60*24*7, '/');

        $user->save();
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
     * @param User $user
     * @return bool
     */
    public static function CheckUserActivity($user){
        if(!@$user->id)
            return false;
        $bans_activity = count(Ban::where('user_id', $user->id)->where('is_active', 1)->whereRaw('is_permanent = 1 OR end_date > ?', [time()])->get()) == 0;
        $status_activity = UserSettings::where('user_id', $user->id)->get()->first()->status > 0;
        return $bans_activity && $status_activity;
    }

    /**
     * @param $email
     * @param $password
     * @param $referral
     * @param $fast
     * @return bool
     */
    public static function CreateNewUser($email, $password, $referral, $fast = false){
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
        $user_settings->nickname = 'NONAME';
        $user_settings->save();

        $user_balance->balance = 0;
        $user_balance->total_spend = 0;
        $user_balance->user_id = $user->id;
        $user_balance->save();

        if($fast)
        {
            $data = [
                'link' => url('/email/confirm/'.MailHelper::NewMailConfirmToken($user->id)),
                'mail_title' => 'Регистрация на сайте ALPHA CHEAT',
                'password' => $password
            ];

            MailHelper::SendMail('mail.types.registration_complete_fast', $data, $user->email, 'Быстрая регистрация :: '.url('/'));
        }
        else
        {
            $data = [
                'link' => url('/email/confirm/'.MailHelper::NewMailConfirmToken($user->id)),
                'mail_title' => 'Регистрация на сайте ALPHA CHEAT',
            ];

            MailHelper::SendMail('mail.types.reg_complete', $data, $user->email, 'Подтверждение регистрации :: '.url('/'));
        }

        return true;
    }

    public static function ResetPassword($user)
    {
        $data = [
            'link' => url('/email/reset_password/'.MailHelper::NewPasswordRecoveryToken($user->id)),
            'mail_title' => 'Восстановление пароля ALPHA CHEAT'
        ];

        MailHelper::SendMail('mail.types.password_reset', $data, $user->email, 'Восстановление пароля :: '.url('/'));
    }
    public static function CheckSteamNick($steam_id){
        if($steam_id == 0)
            return false;
        try{
            $user_data = simplexml_load_file("http://steamcommunity.com/profiles/$steam_id?xml=1", null, LIBXML_NOCDATA);
            return strpos(@$user_data->steamID, 'alphacheat.com') !== false;
        }catch(\ErrorException $ex){
            return false;
        }
    }

    public static function SubscriptionActive($subscription_id){
        $current_time = time();
        $subscription_settings = @SubscriptionSettings::where('subscription_id', $subscription_id)->where('end_date', '>', $current_time)->get();
        if(!count($subscription_settings))
            return false;

        return true;
    }
    public static function NewPassword($length){
        if($length <= 0)
            return "";
        $arr = array(
            'a','b','c','d','e','f',
            'g','h','i','j','k','l',
            'm','n','o','p','r','s',
            't','u','v','x','y','z',
            'A','B','C','D','E','F',
            'G','H','I','J','K','L',
            'M','N','O','P','R','S',
            'T','U','V','X','Y','Z',
            '1','2','3','4','5','6',
            '7','8','9','0');

        $newPass  = "";
        for($i = 0; $i < $length; $i++)
            $newPass .= $arr[rand(0, count($arr) - 1)];

        return $newPass;
    }

    public static function MakePayment($user_id, UserInvoice $user_invoice, $out_sum, $inv_id){
        $user_balance = Balance::where('user_id', $user_id)->get()->first();
        $user_settings = UserSettings::where('user_id', $user_id)->get()->first();
        $user_referral = null;

        if(@$user_settings->referral){
            $user_referral = UserSettings::where('user_id', $user_settings->referral)->get()->first();
            $referral_balance = Balance::where('user_id', $user_settings->referral)->get()->first();
            $referral_balance->balance += $out_sum * ($user_referral->is_partner ? 0.2 : 0.1);
            $referral_balance->save();
        }

        $user_balance->balance += $out_sum;
        $user_balance->total_spend += $out_sum;
        $user_balance->save();

        $user_settings->status = 2;
        $user_settings->save();

        $user_invoice->active = 0;
        $user_invoice->save();

        $payment_history = new PaymentHistory();
        $payment_history->user_id = $user_id;
        $payment_history->date = time();
        $payment_history->amount = $out_sum;
        $payment_history->description = "Пополнение баланса на ".CostHelper::Format($out_sum)[1]." ID счета [".$inv_id."]";
        $payment_history->sign = hash("sha256", "$out_sum :: $payment_history->description".base64_encode(openssl_random_pseudo_bytes(64)));
        $payment_history->save();
    }
}