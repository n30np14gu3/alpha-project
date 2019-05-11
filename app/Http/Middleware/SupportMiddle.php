<?php

namespace App\Http\Middleware;

use App\Http\Helpers\UserHelper;
use Closure;

class SupportMiddle
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
        return $next($request);
    }
}
