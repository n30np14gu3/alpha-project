<?php

namespace App\Http\Middleware;

use App\Http\Helpers\UserHelper;
use Closure;

class AdminActionMiddle
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
        if(!UserHelper::CheckUserActivity($user)){
            return json_encode([
                'status' => 'ERROR',
                'message' => 'Аккаунт имеет ограничения!'
            ]);
        }
        if($user->staff_status < 1){
            return json_encode([
                'status' => 'ERROR',
                'message' => 'Недостаточно прав для выполнения запроса!'
            ]);
        }

        $route = $request->route()->getName();
        switch ($route){
            case 'module_create':
            case 'create_country':
            case 'create_game':
            case 'get_game_data':
            case 'update_game':
            case 'create_increment':
            case 'create_cost':
            case 'create_product_feature':
            case 'create_product':
                if($user->staff_status < 3){
                    return json_encode([
                        'status' => 'ERROR',
                        'message' => 'Недостаточно прав для выполнения запроса!'
                    ]);
                }
                break;
        }
        return $next($request);
    }
}
