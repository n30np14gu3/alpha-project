<?php

namespace App\Http\Controllers;

use App\Http\Helpers\UserHelper;
use App\Models\UserSettings;
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
        $log->date = date("Y-m-d H:i:s");
        $log->info = Geolocation::getLocationInfo();
        $log->save();
        $request->session()->put('user_session', $user_session);
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

        return $result;
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
}
