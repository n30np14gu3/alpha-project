<?php

namespace App\Http\Controllers;

use App\Models\PaymentHistory;
use App\Models\Ticket;
use Illuminate\Http\Request;


use App\Models\Game;
use App\Models\Country;
use App\Models\GameModule;
use App\Models\Product;
use App\Models\ProductCost;
use App\Models\ProductFeature;
use App\Models\ProductIncrement;
use App\Models\Subscription;
use App\Models\SubscriptionSettings;
use App\Models\User;
use App\Models\UserSettings;
use App\Models\LoginHistory;
use App\Models\Balance;
use App\Models\BalanceFund;
use App\Models\Ban;

use App\Http\Helpers\Geolocation;
use App\Http\Helpers\UserHelper;
use App\Http\Helpers\CostHelper;

class adminController extends Controller
{
    public function index(Request $request){
        $user = UserHelper::CheckAuth($request, true);

        $settings = @UserSettings::where('user_id', $user->id)->get()->first();

        $data = [
            'logged' => true,
            'user_data' => [
                'base' => $user,
                'settings' => $settings,
            ],
        ];

        return view('pages.webmaster', $data);
    }
}
