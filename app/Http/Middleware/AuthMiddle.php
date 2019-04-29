<?php

namespace App\Http\Middleware;

use Closure;

use App\Http\Helpers\UserHelper;
class AuthMiddle
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
        if(!UserHelper::CheckAuth($request))
            return redirect()->route('index');

        return $next($request);
    }
}
