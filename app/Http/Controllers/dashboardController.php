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

class dashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = UserHelper::CheckAuth($request, true);
        if(!@$user->id)
            return redirect()->route('logout');

        $settings = @UserSettings::where('user_id', $user->id)->get()->first();
        $balance = @Balance::where('user_id', $user->id)->get()->first();
        $ref_nickname = @UserSettings::where('user_id', $settings->referral)->get()->first()->nickname;
        $data = [
            'logged' => true,
            'user_data' => [
                'base' => $user,
                'settings' => $settings,
                'balance' => CostHelper::GetBalance($balance->balance, $request),
                'invitor' => $ref_nickname ? $ref_nickname : "NONAME",
                'login_history' => @LoginHistory::where('user_id', $user->id)->get(),
                'referrals' => @UserSettings::where('referral', $user->id)->where('status', '>', 0)->get(),
                'has_steam' => $settings->steam_id != 0,
                'has_domain' => UserHelper::CheckSteamNick($settings->steam_id),
                'steam_link' => ($settings->steam_id != 0) ? 'http://steamcommunity.com/profiles/'.$settings->steam_id : '',
                'balance_costs' => [
                    CostHelper::Convert(2, $request),
                    CostHelper::Convert(4, $request),
                    CostHelper::Convert(10, $request),
                    CostHelper::Convert(20, $request),
                    CostHelper::Convert(40, $request)
                    ]
            ]
        ];
        return view('pages.dashboard', $data);
    }

    public function verifyAccount(){
        $rsp = [
            'status' => 'ERROR',
        ];
        $link = @$_POST['link'];
        if(strpos($link, 'steamcommunity.com'))
        {
            $obj = simplexml_load_file($link."?xml=1", null, LIBXML_NOCDATA);
            if(strpos($obj->steamID, env('APP_DOMAIN')))
                $rsp['status'] = 'OK';
            else
                $rsp['status'] = 'ERROR';
        }
        return json_encode($rsp);
    }
}
