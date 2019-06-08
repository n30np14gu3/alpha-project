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

        $staff_data = [
            'support_tickets' => [],
            'games' =>[
                'base' => [],
                'modules' => [],
            ],
            'products' => [
                'base' => [],
                'modules' => [],
                'increments' => [],
                'costs' => []
            ],
            'countries' => [],
        ];

        $tickets = Ticket::all();
        foreach($tickets as $ticket)
        {
            array_push($staff_data['support_tickets'],
                [
                    'base' => $ticket,
                    'user' => User::where('id', $ticket->user_id)->get()->first(),
                    'is_empty' => $ticket->user_id != null,
                    'is_my' => $ticket->user_id == $user->id
                ]);
        }
        if($user->staff_status >=3){
            $staff_data['games']['base'] = Game::all();
            $staff_data['games']['modules'] = GameModule::all();

            $staff_data['products']['base'] = Product::all();
            $staff_data['products']['modules'] = ProductFeature::all();
            $staff_data['products']['increments'] = ProductIncrement::all();
            $staff_data['products']['costs'] = ProductCost::all();

        }

        if($user->staff_status == 4){
            $staff_data['countries'] = Country::all();
        }

        $data = [
            'logged' => true,
            'user_data' => [
                'base' => $user,
                'settings' => $settings,
            ],

            'staff_data' => $staff_data
        ];

        return view('pages.webmaster', $data);
    }
}
