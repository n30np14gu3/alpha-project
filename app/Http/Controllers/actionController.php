<?php

namespace App\Http\Controllers;

use App\Http\Helpers\UserHelper;
use Illuminate\Http\Request;


use App\Models\User;
use App\Models\LoginHistory;

use App\Http\Helpers\CryptoHelper;
use App\Http\Helpers\Geolocation;

class actionController extends Controller
{
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

    public function logout(Request $request){
        $request->session()->flush();
        if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                unset($_COOKIE[$name]);
            }
        }
        return redirect('/');
    }

    public function login(Request $request){
        $result = [
          'status' => 'ERROR',
          'message' => 'UNKNOWN ERROR!'
        ];


        $email = @$_POST['email'];
        $password = hash("sha256", @$_POST['password']);
        $remember = @$_POST['save'];

        $user = User::where('email', $email)->where('password', $password)->get()->first();
        if(!$user){
            $result['message'] = "Неверный логин или пароль!";
            return json_encode($result);
        }

        $user_data = UserHelper::GetUserData($email, $password);
        $user_session = CryptoHelper::EncryptResponse(json_encode($user_data));

        if($remember)
            setcookie('user_session', $user_session, time() + 60*60*24*7, '/');

        unset($_COOKIE['referral']);

        $log = new LoginHistory();
        $log->user_id = $user->id;
        $log->ip = $user_data['ip'];
        $log->date = date("Y-m-d H:i:s");
        $log->info = Geolocation::getLocationInfo();
        $log->save();
        $request->session()->put('user_session', $user_session);
        return json_encode($result);
    }

    public function register(Request $request){
        $result = [
            'status' => 'ERROR',
            'message' => 'UNKNOWN ERROR!'
        ];

        if($request->session()->has('user_session')){
            return json_encode($result);
        }

        $email = @$_POST['email'];
        $password = @$_POST['password'];
        $password2 = @$_POST['password-2'];
        $confirm = @$_POST['confirm'];
        $referral = @$_COOKIE['referral'];

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
}
