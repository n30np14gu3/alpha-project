<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\User;
use App\Helpers\CryptoHelper;

class actionMiddle
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->route()->getName() != 'logout')
        {
            $user_data = @$_COOKIE['user_session'];
            if(!$user_data)
                $user_data = @$request->session()->get('user_session');

            $user_data = (array)json_decode(CryptoHelper::DecryptResponse($user_data));
            if(!$user_data)
                return redirect()->route('logout');

            if($_SERVER['REMOTE_ADDR'] != $user_data['ip'])
                return redirect()->route('logout');

            $user = User::where('email', @$user_data['email'])->where('password', @$user_data['password'])->get()->first();
            if(!$user)
                return redirect()->route('logout');
        }
        return $next($request);
    }
}
