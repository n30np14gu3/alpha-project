<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;

use App\Http\Helpers\UserHelper;

class lendingController extends Controller
{
    public function index(Request $request){
        $data = [
            'lending' => true,
            'logged' => false
        ];

        if(!UserHelper::CheckAuth($request)){
            $data['logged']  = true;
        }

        return view('pages.main', $data);
    }

    public function referer(Request $request, $refId){
        if(@User::where('referral_code', $refId)->get()->first() && UserHelper::CheckAuth($request) == 1)
            setcookie('referrer', $refId, time() + 60*60*24, '/');
        else
            return redirect('/');
        return view('pages.main');
    }

    public function changeLang(Request $request, $lang){
        switch($lang) {
            case 'ru':
            case 'us':
                setcookie('lang', $request->route('lang'), time() + 60 * 60 * 24 * 365 * 5, '/');
                return back();
            default:
                return back();
                break;
        }
    }
}
