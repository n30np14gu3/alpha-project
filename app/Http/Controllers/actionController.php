<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\User;

use App\Helpers\CryptoHelper;

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

        $user_data = [
            'email' => '',
            'password' => '',
            'ip' => '',
            'salt' => ''
        ];

        $email = @$_POST['email'];
        $password = hash("sha256", @$_POST['password']);
        $remember = @$_POST['save'];

        $user = User::where('email', $email)->where('password', $password)->get()->first();
        if(!$user){
            $result['message'] = "Неверный логин или пароль!";
            return json_encode($result);
        }

        $user_data['email'] = $email;
        $user_data['password'] = $password;
        $user_data['ip'] = $_SERVER['REMOTE_ADDR'];
        $user_data['salt'] = hash("sha256", base64_encode(openssl_random_pseudo_bytes(64)));

        $user_session = CryptoHelper::EncryptResponse(json_encode($user_data));

        if($remember)
            setcookie('user_session', $user_session, time() + 60*60*24*7, '/');

        $request->session()->put('user_session', $user_session);
        return json_encode($result);
    }
}
