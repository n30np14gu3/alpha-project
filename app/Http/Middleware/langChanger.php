<?php

namespace App\Http\Middleware;

use Closure;

class langChanger
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
        switch($request->route('lang')){
            case 'ru':
            case 'us':
                setcookie('lang', $request->route('lang'), time() + 60*60*24*365*5, "/");
                break;
            default:
                return redirect('/');
                break;

        }
        return $next($request);
    }
}
