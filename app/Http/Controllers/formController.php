<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class formController extends Controller
{
    public function login(Request $request)
    {
        return view('pages.modules.auth.login');
    }

    public function register(Request $request)
    {
        return view('pages.modules.auth.register');
    }

    public function resetPassword(Request $request){
        return view('pages.modules.auth.reset_password');
    }
}
