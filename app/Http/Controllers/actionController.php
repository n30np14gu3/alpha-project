<?php

namespace App\Http\Controllers;

use App\Http\Helpers\UserHelper;
use App\Models\Balance;
use App\Models\Game;
use App\Models\GameModule;
use App\Models\PaymentHistory;
use App\Models\Product;
use App\Models\ProductCost;
use App\Models\ProductIncrement;
use App\Models\Subscription;
use App\Models\SubscriptionSettings;
use App\Models\UserSettings;
use App\Models\Country;
use Illuminate\Http\Request;


use App\Models\User;
use App\Models\LoginHistory;

use App\Http\Helpers\CryptoHelper;
use App\Http\Helpers\Geolocation;
use App\Http\Helpers\ReCaptcha;

class actionController extends Controller
{
    /**
     * Non middleware
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(Request $request){
        $request->session()->flush();
        if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                setcookie($name, '', time() - 100, '/');
            }
        }
        return redirect('/');
    }

    /**
     * Non middleware
     * @param Request $request
     * @return false|string
     */
    public function login(Request $request){
        if(UserHelper::CheckAuth($request) != 1)
            return redirect()->route('logout');

        $result = [
          'status' => 'ERROR',
          'message' => 'UNKNOWN ERROR!'
        ];


        $email = @$_POST['email'];
        $password = hash("sha256", @$_POST['password']);
        $remember = @$_POST['save'];

        if(!env('BETA_DISABLERECAPTCHA') && !ReCaptcha::Verify())
        {
            $result['message'] = "Ошибка ReCaptcha!";
            return json_encode($result);
        }

        $user = User::where('email', $email)->where('password', $password)->get()->first();
        if(!$user){
            $result['message'] = "Неверный логин или пароль!";
            return json_encode($result);
        }

        $user_data = UserHelper::CreateUserArray($user->id, $email, $password);
        $user_session = CryptoHelper::EncryptResponse(json_encode($user_data));

        if($remember)
            setcookie('user_session', $user_session, time() + 60*60*24*7, '/');

        setcookie('referral', null, time() - 100, '/');

        $log = new LoginHistory();
        $log->user_id = $user->id;
        $log->ip = $user_data['ip'];
        $log->date = time();
        $log->info = Geolocation::getLocationInfo();
        $log->save();
        $request->session()->put('user_session', $user_session);
        $result['status'] = "OK";
        return json_encode($result);
    }

    function fastRegister(Request $request){
        if(UserHelper::CheckAuth($request) != 1)
            return redirect()->route('logout');

        $result = [
            'status' => 'ERROR',
            'message' => 'UNKNOWN ERROR!'
        ];

        if($request->session()->has('user_session')){
            return json_encode($result);
        }

        if(!env('BETA_DISABLERECAPTCHA') && !ReCaptcha::Verify())
        {
            $result['message'] = "Ошибка ReCaptcha!";
            return json_encode($result);
        }

        $email = @$_POST['email'];
        $referral = @$_COOKIE['referrer'];
        $password =  UserHelper::NewPassword(16);
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $result['message']  = "Формат почтового ящика неправильный";
            return json_encode($result);
        }

        if(!UserHelper::CreateNewUser($email, $password, $referral, true)){
            $result['message']  = "Данный email уже зарегестрирован в системе";
            return json_encode($result);
        }

        $result['status'] = "OK";
        return json_encode($result);
    }

    /**
     * Non middleware
     * @param Request $request
     * @return false|string
     */
    public function register(Request $request){
        if(UserHelper::CheckAuth($request) != 1)
            return redirect()->route('logout');
        $result = [
            'status' => 'ERROR',
            'message' => 'UNKNOWN ERROR!'
        ];

        if($request->session()->has('user_session')){
            return json_encode($result);
        }

        if(!env('BETA_DISABLERECAPTCHA') && !ReCaptcha::Verify())
        {
            $result['message'] = "Ошибка ReCaptcha!";
            return json_encode($result);
        }

        $email = @$_POST['email'];
        $password = @$_POST['password'];
        $password2 = @$_POST['password-2'];
        $confirm = @$_POST['confirm'];
        $referral = @$_COOKIE['referrer'];

        if(!$email || !$password || !$password2 || !$confirm){
            $result['message']  = "Одно или несколько полей пустые!";
            return json_encode($result);
        }

        if($password != $password2){
            $result['message']  = "Введенные пароли не совпадают!";
            return json_encode($result);
        }


        if(strlen($password) < 8 && strlen($password) > 30){
            $result['message'] = 'Пароль имеет неверную длину';
            return json_encode($result);
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $result['message']  = "Формат почтового ящика неправильный";
            return json_encode($result);
        }

        if(!UserHelper::CreateNewUser($email, $password, $referral)){
            $result['message']  = "Данный email уже зарегестрирован в системе";
            return json_encode($result);
        }

        $result['status'] = "OK";
        return json_encode($result);
    }


    /**
     * Non middleware
     * @param Request $request
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public function resetPassword(Request $request)
    {
        if(UserHelper::CheckAuth($request) != 1)
            return redirect()->route('logout');
        $result = [
            'status' => 'ERROR',
            'message' => 'UNKNOWN ERROR!'
        ];

        if(!env('BETA_DISABLERECAPTCHA') && !ReCaptcha::Verify())
        {
            $result['message'] = "Ошибка ReCaptcha!";
            return json_encode($result);
        }

        $mail = @$_POST['email'];
        if(!$mail){
            $result['message'] = "Вы указали пустую почту";
            return json_encode($result);
        }

        if(!filter_var($mail, FILTER_VALIDATE_EMAIL)){
            $result['message']  = "Формат почтового ящика неправильный";
            return json_encode($result);
        }

        $user = User::where('email', $mail)->get()->first();
        if(!$user){
            $result['message']  = "Данный email не зарегестрирован в системе";
            return json_encode($result);
        }

        UserHelper::ResetPassword($user);
        $result['status'] = "OK";
        return json_encode($result);
    }

    public function verifySteam(Request $request)
    {
        $result = [
            'status' => 'ERROR',
            'message' => 'UNKNOWN ERROR'
        ];

        $link = @$_POST['link'];
        if(!$link){
            $result['message'] = 'Ссылка пустая!';
            return json_encode($result);
        }

        if(strpos($link, 'https://steamcommunity.com') !== 0 || count(explode('/', $link)) != 5){
            $result['message'] = 'Сссылка имеет неверный формат!';
            return json_encode($result);
        }

        $user_data = simplexml_load_file("$link?xml=1", null, LIBXML_NOCDATA);
        if(strpos(@$user_data->steamID, 'alphacheat.com') === false){
            $result['message'] = 'Имя пользователя не содержит имени домена';
            return json_encode($result);
        }

        $user_info = UserHelper::GetLocalUserInfo($request);
        if(!@$user_info['id'])
            return redirect()->route('logout');

        $user = UserSettings::where('user_id', $user_info['id'])->get()->first();
        if(@UserSettings::where('steam_id', $user_data->steamID64)->get()->first()->user_id != 0){
            $result['message'] = 'Данный steam аккаунт уже привязан к другому пользователю.';
            return json_encode($result);
        }
        if($user->steam_id){
            $result['message'] = 'Аккаунт уже привязан к steam';
            return json_encode($result);
        }

        $user->steam_id = $user_data->steamID64;
        $user->save();
        $result['status'] = "OK";
        return json_encode($result);
    }

    public function saveInfo(Request $request){
        $result = [
            'status' => 'ERROR',
            'message' => 'UNKNOWN ERROR'
        ];

        $nickname = @$_POST['account']['nickname'];
        $birthday = @$_POST['account']['birthday'];
        $first_name = @$_POST['account']['first-name'];
        $last_name = @$_POST['account']['last-name'];
        $sex = abs((int)@$_POST['account']['sex']) % 3;

        if(!$nickname || !$first_name || !$last_name || !$sex) {
            $result['message'] = 'Не все поля заполнены!';
            return json_encode($result);
        }

        if(!preg_match('/^[aA-zZ0-9]{1,25}$/', $nickname)) {
            $result['message'] = 'Никнейм имеет неверный формат';
            return json_encode($result);
        }

        if(!strtotime($birthday) && $birthday) {
            $result['message'] = 'Дата рождения имеет неверный формат';
            return json_encode($result);
        }

        if(time() - strtotime($birthday) < 60*60*24*365*14){
            $result['message'] = 'Вам должно быть больше 14 лет!';
            return json_encode($result);
        }

        if(!preg_match('/^[aA-zZ0-9aA-яЯ]{1,25}$/', $first_name)){
            $result['message'] = 'Имя имеет неверный формат';
            return json_encode($result);
        }

        if(!preg_match('/^[aA-zZ0-9aA-яЯ]{1,25}$/', $last_name)){
            $result['message'] = 'Фамилия имеет неверный формат';
            return json_encode($result);
        }

        $user_info = UserHelper::GetLocalUserInfo($request);
        $user_settings = UserSettings::where('user_id', $user_info['id'])->get()->first();
        $user_settings->nickname = $nickname;
        if(!$user_settings->birth_date)
            $user_settings->birth_date = date("Y-m-d H:i:s", strtotime($birthday));
        $user_settings->first_name = $first_name;
        $user_settings->last_name = $last_name;
        $user_settings->sex = $sex;
        $user_settings->save();


        $result['status'] = "OK";
        return json_encode($result);
    }

    public function changePassword(Request $request){
        $result = [
            'status' => 'ERROR',
            'message' => 'UNKNOWN ERROR'
        ];

        $old_password = @$_POST['old-password'];
        $new_password = @$_POST['new-password'];
        $new_password_2 = @$_POST['new-password-2'];

        if(!$old_password || !$new_password || !$new_password_2){
            $result['message'] = 'Не все поля заполнены!';
            return json_encode($result);
        }

        if($new_password_2 != $new_password){
            $result['message'] = 'Введенные пароли не совпадают!';
            return json_encode($result);
        }

        $user_data = UserHelper::GetLocalUserInfo($request);
        if($user_data['password'] != hash("sha256", $old_password)){
            $result['message'] = 'Введен неверный старый пароль!';
            return json_encode($result);
        }

        UserHelper::UpdateUserPassword($request, $new_password);
        $result['status'] = 'OK';
        return json_encode($result);
    }

    public function purchase(Request $request){
        $result = [
            'status' => 'ERROR',
            'message' => 'UNKNOWN ERROR'
        ];

        $cost_id = @$_POST['cid'];
        $product_id = @$_POST['pid'];

        if(!$cost_id || !$product_id){
            $result['message'] = 'Не все поля заполнены!';
            return json_encode($result);
        }

        $product = @Product::where('id', $product_id)->get()->first();
        if(!$product){
            $result['message'] = 'Данного продукта не существует';
            return json_encode($result);
        }

        $product_cost = @ProductCost::where('id', $cost_id)->get()->first();
        if(!$product_cost){
            $result['message'] = 'Такой цены не существует';
            return json_encode($result);
        }

        $product_costs = explode(",", $product->costs);
        if(!in_array($product_cost->id, $product_costs)){
            $result['message'] = 'Цена принадлежит другому продукту';
            return json_encode($result);
        }

        $user_country = @json_decode(Geolocation::getLocationInfo())->geoplugin_countryCode;
        $country_id = @Country::where('code', $user_country)->get()->first()->id;
        if(!$country_id)
            $country_id = 1;

        if($product_cost->country_id != $country_id){
            $result['message'] = 'Данный продукт недоступен в Вашем регионе';
            return json_encode($result);
        }

        $user_info = UserHelper::GetLocalUserInfo($request);
        $user_balance = Balance::where('user_id', $user_info['id'])->get()->first();

        $user_settings = UserSettings::where('user_id', $user_info['id'])->get()->first();
        if(UserHelper::CheckSteamNick($user_settings->steam_id))
            $product_cost->cost *= 0.97;

        if($user_balance->balance < $product_cost->cost){
            $result['message'] = 'На счету недостаточно средств!';
            return json_encode($result);
        }

        $product_increment = @ProductIncrement::where('id', $product_cost->increment_id)->get()->first();

        $user_subscription = @Subscription::where('user_id', $user_info['id'])->get()->first();
        $product_features = @GameModule::whereIn('id',explode(",", $product->game_modules))->get();
        $product_game = @Game::where('id', $product->game_id)->get()->first();
        $current_time = time();

        if(!$user_subscription){
            $user_subscription = new Subscription();
            $user_subscription->game_id = $product->game_id;
            $user_subscription->user_id = $user_info['id'];
            $user_subscription->status = 1;
            $user_subscription->activation_date = date("Y-m-d H:i:s");
            $user_subscription->save();

            foreach($product_features as $feature){
                $subscription_setting = new SubscriptionSettings();
                $subscription_setting->subscription_id = $user_subscription->id;
                $subscription_setting->module_id = $feature->id;
                $subscription_setting->end_date = $current_time + $product_increment->increment;
                $subscription_setting->save();
            }
        }
        else{
            foreach($product_features as $feature){
                $subscription_setting = SubscriptionSettings::where('subscription_id', $user_subscription->id)->where('module_id', $feature->id)->get()->first();
                if(!@$subscription_setting){
                    $subscription_setting = new SubscriptionSettings();
                    $subscription_setting->subscription_id = $user_subscription->id;
                    $subscription_setting->module_id = $feature->id;
                    $subscription_setting->end_date = $current_time + $product_increment->increment;
                }
                else{
                    $subscription_setting->end_date =
                        $subscription_setting->end_date < $current_time ?
                            $current_time + $product_increment->increment :
                            $subscription_setting->end_date + $product_increment->increment;
                }
                $subscription_setting->save();
            }
        }

        $user_balance->balance -= $product_cost->cost;
        $user_balance->total_spend += $product_cost->cost;
        $user_balance->save();

        $payment_log = new PaymentHistory();
        $payment_log->user_id = $user_info['id'];
        $payment_log->amount = $product_cost->cost;
        $payment_log->date = $current_time;
        $payment_log->description = "[$product_game->name] :: $product->title ($product_increment->title)";
        $payment_log->sign = hash("sha256","$payment_log->description :: ".base64_encode(openssl_random_pseudo_bytes(64)).time());
        $payment_log->save();

        $result['status'] = 'OK';
        return json_encode($result);
    }
}
