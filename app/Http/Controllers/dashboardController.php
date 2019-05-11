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



class dashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = UserHelper::CheckAuth($request, true);
        if(!@$user->id)
            return redirect()->route('logout');

        $settings = @UserSettings::where('user_id', $user->id)->get()->first();
        $balance = @Balance::where('user_id', $user->id)->get()->first();
        $ref_nickname = @UserSettings::where('user_id', $settings->referral)->get()->first();
        $ref_nickname = @$ref_nickname->nickname;
        $has_domain = UserHelper::CheckSteamNick($settings->steam_id);
        $user_country = @json_decode(Geolocation::getLocationInfo())->geoplugin_countryCode;
        $country_id = @Country::where('code', $user_country)->get()->first()->id;

        $subscriptions = [];
        $subscriptions_db = @Subscription::where('user_id', $user->id)->get();

        foreach($subscriptions_db as $sub)
        {
            $subscription_info = [
                'game' => null,
                'modules' => []
            ];

            $subscription_info['game'] = Game::where('id', $sub->game_id)->get()->first();
            $subscription_settings = SubscriptionSettings::where('subscription_id', $sub->id)->get();
            foreach($subscription_settings as $s){
                $module_info = [
                    'name' => '',
                    'end_date' => 0
                ];
                $module_info['end_date'] = date("d-m-Y H:i:s", $s->end_date);
                $module_info['name'] = GameModule::where('id', $s->module_id)->get()->first()->name;
                array_push($subscription_info['modules'], $module_info);
            }
            array_push($subscriptions, $subscription_info);
        }

        $products = [];
        $products_db = Product::all();

        foreach($products_db as $product)
        {
            $product_module = [
                'id' => @$product->id,
                'title' => @$product->title,
                'game' => @Game::where('id', $product->game_id)->get()->first(),
                'costs' => [],
                'features' => []
            ];

            $product_costs = ProductCost::where('country_id', $country_id)->get();
            if(!count($product_costs)){
                $usa = Country::where('code', 'us')->get()->first();
                $product_costs = ProductCost::where('country_id', $usa->id)->where('product_id', $product->id)->get();
            }
            foreach($product_costs as $costs){
                $cost_module = [
                    'cid' => $costs->id,
                    'increment' => ProductIncrement::where('id', $costs->increment_id)->get()->first(),
                    'cost' => CostHelper::Convert($costs->cost, $request)
                ];
                if($has_domain)
                    $cost_module['cost'] *= 0.97;
                array_push($product_module['costs'], $cost_module);
            }

            $features = ProductFeature::where('product_id', $product->id)->get();
            foreach($features as $feature){
                array_push($product_module['features'], $feature);
            }

            array_push($products, $product_module);
        }

        $balance_funds = [];
        $balance_funds_db = BalanceFund::where('country_id', $country_id)->get()->sortBy('amount');
        foreach($balance_funds_db as $fund) {
            array_push($balance_funds, CostHelper::Format($fund->amount));
        }

        $bans = [
            'exist' => false,
            'data' => []
        ];
        $bans_db = Ban::where('user_id', $user->id)->where('is_active', 1)->whereRaw('is_permanent = 1 OR end_date > ?', [time()])->get();
        if(count($bans_db) > 0){
            foreach($bans_db as $ban){
                $ban_module = [
                    'submit_date' => date("d-m-Y H:i:s", $ban->submit_date),
                    'end_date' => $ban->is_permanent ? 'Навсегда' : date("d-m-Y H:i:s", $ban->end_date),
                    'staff_nickname' => UserSettings::where('user_id', $ban->staff_id)->get()->first()->nickname,
                    'reason' => $ban->reason,
                    'token' => $ban->token
                ];
                array_push($bans['data'], $ban_module);
            }
            $bans['exist'] = true;
        }

        $staff_data = [
            'support_tickets' => [],

            'users' => [],
            'user_settings' => [],
            'bans' => [],

            'games' => [],
            'game_modules' => [],

            'products' => [],
            'product_modules' => [],

            'countries' => [],

        ];
        if($user->staff_status >=1){
            $tickets = Ticket::where('completed', 0)->get();
            foreach($tickets as $ticket){
                array_push($staff_data['support_tickets'], [
                    'base' => $ticket,
                    'user' => User::where('id', $ticket->user_id)->get()->first(),
                    'is_empty' => $ticket->staff_id == null,
                    'is_my' => $ticket->staff_id == $user->id
                ]);
            }

            if($user->staff_status >= 2){
                $staff_data['users'] = User::all();
                $staff_data['user_settings'] = UserSettings::all();
                $staff_data['bans'] = Ban::all();
            }

            if($user->staff_status >= 3){
                $staff_data['games'] = Game::all();
                $staff_data['game_modules'] = GameModule::where('game_id', null)->get();

                $staff_data['products'] = Product::all();
                $staff_data['product_modules'] = ProductFeature::all();

                $staff_data['countries'] = Country::all();
            }

        }
        $data = [
            'logged' => true,
            'user_data' => [
                'base' => $user,
                'settings' => $settings,
                'balance' => CostHelper::Convert($balance->balance, $request),
                'invitor' => $ref_nickname,
                'login_history' => @LoginHistory::where('user_id', $user->id)->get(),
                'referrals' => @UserSettings::where('referral', $user->id)->where('status', '>', 0)->get(),
                'has_steam' => $settings->steam_id != 0,
                'has_domain' =>$has_domain,
                'steam_link' => ($settings->steam_id != 0) ? 'https://steamcommunity.com/profiles/'.$settings->steam_id : '',
                'subscriptions' => $subscriptions,
                'payment_history' => PaymentHistory::where('user_id', $user->id)->get(),
                'bans' => $bans
            ],
            'balance_funds' => $balance_funds,
            'products' => $products,
            'staff_data' => $staff_data
        ];
        return view('pages.dashboard', $data);
    }

    public function downloadLoader(Request $request, $game_id){
        $headers = [
            'Content-Type: application/zip, application/octet-stream'
        ];

        $game = @Game::where('id', $game_id)->get()->first();
        if(!$game)
            return redirect()->route('dashboard');

        $user = UserHelper::CheckAuth($request, true);
        if(!UserHelper::CheckUserActivity($user))
            return redirect()->route('dashboard');

        $user_subscription = @Subscription::where('user_id', $user->id)->where('game_id', $game_id)->get()->first();
        if(!$user_subscription)
            return redirect()->route('dashboard');

        if(!UserHelper::SubscriptionActive($user_subscription->id))
            return redirect()->route('dashboard');

        return response()->download($game->loader_path, 'loader.7zip', $headers);
    }
}
