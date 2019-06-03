<?php

namespace App\Http\Middleware;

use App\Http\Helpers\UserHelper;
use Closure;

class AdminMiddle
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = UserHelper::CheckAuth($request, true);
        if (!UserHelper::CheckUserActivity($user)) {
            return back();
        }

        if(@$user->staff_status < 1){
            return back();
        }

        return $next($request);
    }
}
