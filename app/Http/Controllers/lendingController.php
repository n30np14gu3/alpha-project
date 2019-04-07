<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;

use App\Http\Helpers\UserHelper;

class lendingController extends Controller
{
    public function index(){
        return view('pages.main');
    }

    public function referer(Request $request, $refId){
        if(@User::where('referral_code', $refId)->get()->first() && !UserHelper::CheckAuth($request, true))
            setcookie('referrer', $refId, time() + 60*60*24, '/');

        else
            return redirect('/');

        $data = [
            '' => ''
        ];
        return view('pages.main', $data);
    }
    public function stub(){
        return redirect('/');
    }
}
