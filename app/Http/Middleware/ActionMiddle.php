<?php

namespace App\Http\Middleware;

use Closure;

use App\Http\Helpers\CryptoHelper;
use App\Http\Helpers\UserHelper;

use App\Models\User;

class ActionMiddle
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


        $user = UserHelper::CheckAuth($request, true);
        if(!@$user->id)
            return redirect()->route('logout');

        if(!UserHelper::CheckUserActivity($user) && $request->route()->getName() != 'dashboard') {
            $response = [
                'status' => 'ERROR',
                'message' => ''
            ];

            $response['message'] = 'Аккаунт имеет ограничения!';
            return json_encode($response);
        }

        return $next($request);
    }
}
