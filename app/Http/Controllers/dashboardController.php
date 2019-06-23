<?php

namespace App\Http\Controllers;


use App\Models\PaymentHistory;
use App\Models\Ticket;
use App\Models\UserInvoice;
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
        $user_country = 'ru';
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
                'modules' => GameModule::whereIn('id', explode(",", $product->game_modules))->get()
            ];

            $product_costs = ProductCost::whereIn('id', explode(",", $product->costs))->where('country_id', $country_id)->orderBy('cost', 'asc')->get();
            if(!count($product_costs)){
                $country_id = Country::where('code', 'ru')->get()->first()->id;
                $product_costs = ProductCost::whereIn('id', explode(",", $product->costs))->where('country_id', $country_id)->orderBy('cost', 'asc')->get();
            }
            //$product_costs = $product_costs->orderBy('cost', 'inc')->get();
            foreach($product_costs as $costs){
                $cost_module = [
                    'cid' => $costs->id,
                    'increment' => ProductIncrement::where('id', $costs->increment_id)->get()->first(),
                    'cost' => CostHelper::Convert(($has_domain ? $costs->cost * 0.97 : $costs->cost), $request),
                ];

                array_push($product_module['costs'], $cost_module);
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
                'bans' => $bans,
                'invoices' => @UserInvoice::where('user_id', $user->id)->where('active', '1')->get()
            ],
            'balance_funds' => $balance_funds,
            'products' => $products,
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

        return response()->download(storage_path("app/loaders/$game->loader_path"), 'loader.zip', $headers);
    }
}
