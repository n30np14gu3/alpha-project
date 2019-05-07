<?php

namespace App\Http\Controllers;

use App\Models\PaymentHistory;
use App\Models\UserInvoice;
use Illuminate\Http\Request;

use App\Http\Helpers\UserHelper;
use App\Http\Helpers\CostHelper;


use App\Models\User;
use App\Models\UserSettings;
use App\Models\Balance;

class paymentController extends Controller
{
    public function prepare(Request $request)
    {
        $amount = @$_POST['payment']['amount'];
        if(!$amount)
            return redirect()->route('dashboard');


        $amount_formatted = CostHelper::Format($amount);
        $amount = CostHelper::Convert($amount, $request);
        $user_info = UserHelper::GetLocalUserInfo($request);
        $user_settings = UserSettings::where('user_id', $user_info['id'])->get()->first();
        $user_settings->temp_invoice_id = (int)(rand(1000000, 2147483647));
        $user_settings->save();


        $data = [
            'logged' => true,
            'form_data' => [
                'shop_id' => env('SHOP_NAME'),
                'amount' => $amount[1],
                'amount_local' => $amount_formatted[1],
                'invoice_id' =>  $user_settings->temp_invoice_id,
                'description' => 'Пополнение кошелька на '.$amount_formatted[1],
                'user_id' => $user_info['id'],
                'email' => $user_info['email'],
                'sign' => ''
            ]
        ];
        $shop_password = env('SHOP_TESTMODE') ? env('SHOP_TEST_PASSWORD_1') : env('SHOP_WORK_PASSWORD_1');

         $sign = hash("sha256", $data['form_data']['shop_id'].':'.$data['form_data']['amount'].':'.$user_settings->temp_invoice_id.':'.$shop_password.':shp_uid='.$data['form_data']['user_id']);
         $data['form_data']['sign'] = $sign;

         return view('pages.payment', $data);
    }

    public function callback(Request $request){
        $out_sum = @$request["OutSum"];
        $inv_id = @$request["InvId"];
        $user_id = @$request["shp_uid"];
        $crc = strtoupper(@$request["SignatureValue"]);
        if(!$out_sum || !$inv_id || !$user_id || !$crc){
            echo "Empty Parameters";
            exit();
        }

        $shop_password = env('SHOP_TESTMODE') ? env('SHOP_TEST_PASSWORD_2') : env('SHOP_WORK_PASSWORD_2');
        $sign = strtoupper(hash("sha256", $out_sum.':'.$inv_id.':'.$shop_password.':shp_uid='.$user_id));

        if($sign != $crc){
            echo "Bad sign";
            exit();
        }

        $user = @User::where('id', $user_id)->get()->first();
        if(!$user){
            echo "User not found!";
            exit();
        }

        if(!UserHelper::CheckUserActivity($user)){
            echo "User's ability is limited";
            exit();
        }

        echo "OK$inv_id\n";

        $user_invoice = new UserInvoice();
        $user_invoice->user_id = $user_id;
        $user_invoice->amount = $out_sum;
        $user_invoice->token = $inv_id;
        $user_invoice->active = 1;
        $user_invoice->save();
    }

    public function success(Request $request){
        $data = [
            'logged' => true,
            'style' => 'error',
            'text' => '',
        ];

        $out_sum = @$request["OutSum"];
        $inv_id = @$request["InvId"];
        $shp_item = @$request["Shp_item"];
        $crc = strtoupper(@$request["SignatureValue"]);
        $user_id = @$request["shp_uid"];

        if(!$out_sum || !$inv_id || !$shp_item || !$crc){
            return redirect()->route('dashboard');
        }

        $shop_password = env('SHOP_TESTMODE') ? env('SHOP_TEST_PASSWORD_2') : env('SHOP_WORK_PASSWORD_2');
        $sign = strtoupper(hash("sha256", $out_sum.':'.$inv_id.':'.$shop_password.':shp_uid='.$user_id));
        if($crc != $sign){
            $data['text'] = 'Неверная подпись платежа!';
            return view('pages.mail', $data);
        }

        $user = @User::where('id', $user_id)->get()->first();
        $user_info = UserHelper::GetLocalUserInfo($request);

        if(!$user){
            $data['text'] = 'Пользователь не найден с подобным ID';
            return view('pages.mail', $data);
        }

        if(@$user->id != $user_info['id']) {
            $data['text'] = 'Оплата совершена от лица другого пользователя!';
            return view('pages.mail', $data);
        }

        if(!UserHelper::CheckUserActivity($user)){
            $data['text'] = 'Возможности данного аккаунта ограничены!';
            return view('pages.mail', $data);
        }

        $user_invoice = UserInvoice::where('user_id', $user_id)->where('token', $inv_id)->where('active', 1)->get()->first();
        if(!$user_invoice){
            $data['text'] = 'Счет с таким id не найден!';
            return view('pages.mail', $data);
        }

        $user_balance = Balance::where('user_id', $user_id)->get()->first();
        $user_settings = UserSettings::where('user_id', $user_id)->get()->first();
        $user_referral = null;

        if(@$user_settings->referral){
            $user_referral = User::where('user_id', $user_settings->referral)->get()->first();
            $referral_balance = Balance::where('user_id', $user_referral->id)->get()->first();
            $referral_balance->balance += $out_sum * 0.1;
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
        $payment_history->user_id = $user_info['id'];
        $payment_history->date = time();
        $payment_history->amount = $out_sum;
        $payment_history->description = "Пополнение баланса на ".CostHelper::Format($out_sum)[1]." ID счета [".$inv_id."]";
        $payment_history->sign = hash("sha256", "$out_sum :: $payment_history->description".base64_encode(openssl_random_pseudo_bytes(64)));
        $payment_history->save();

        $data['style'] = 'success';
        $data['text'] = 'Оплата произошла успешно!';

        return view('pages.mail', $data);
    }

    public function fail(){
        $data = [
            'logged' => true,
            'style' => 'error',
            'text' => 'Оплата не удалась',
        ];
        
        return view('pages.mail', $data);
    }
}
