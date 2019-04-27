<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Helpers\UserHelper;
use App\Http\Helpers\CostHelper;


use App\Models\User;
use App\Models\UserSettings;
use App\Models\LoginHistory;
use App\Models\Balance;
use App\Models\Ban;

class paymentController extends Controller
{
    public function prepare(Request $request){
        $amount = 15;
        $desc = 15;
        $local_currency = 'RUB';

        if(!$amount || !$desc || !$local_currency)
            return redirect()->route('dashboard');

        $user = UserHelper::GetLocalUserInfo($request);
        $sign_data = [
            'shop' => env('SHOP_ID'),
            'payment' => time(),
            'amount' => $amount,
            'description' => $desc,
            'currency' => env('SHOP_CURRENCY'),
            'uv_uid' => $user['id']
        ];

        ksort($sign_data, SORT_STRING);
        $sign = hash('sha256', implode(':',$sign_data).':'.env('SHOP_SECRET'));

        $data = [
            'logged' => true,
            'payment_data' => [
                'description' => $desc,
                'email'=> $user['email'],
                'sign'=> $sign,
                'amount_local' => $local_currency,
                'form_data' => $sign_data
            ]
        ];
        return view('pages.payment', $data);
    }

    public function callback(){

    }

    public function success(){

    }

    public function error(){

    }
}
