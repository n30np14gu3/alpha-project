<?php

namespace App\Http\Controllers;

use App\Http\Helpers\UserHelper;
use App\Models\User;
use App\Models\UserSettings;
use Illuminate\Http\Request;

use App\Http\Requests;

class formController extends Controller
{
    public function login(){
        return view('pages.modules.auth.login');
    }

    public function register()
    {
        return view('pages.modules.auth.register');
    }

    public function resetPassword(){
        return view('pages.modules.auth.reset_password');
    }

    public function changeEmail(Request $request){
        $user_data = UserHelper::GetLocalUserInfo($request);
        $user = @UserSettings::where('user_id', $user_data['id'])->get()->first();
        if(@$user->status != 0)
            return back();

        $data = ['logged' => true];
        return view('pages.modules.dashboard.change_email', $data);
    }
}
