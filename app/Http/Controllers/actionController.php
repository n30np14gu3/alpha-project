<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\User;

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

    public function logout(){
        $_SESSION = array();
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
}
