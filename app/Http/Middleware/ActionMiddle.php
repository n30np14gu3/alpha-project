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
        if($request->route()->getName() != 'logout')
        {
            if(!UserHelper::CheckAuth($request))
                return redirect()->route('logout');
        }

        return $next($request);
    }
}
