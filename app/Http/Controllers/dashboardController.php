<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Encryption\EncryptException;
use Illuminate\Contracts\Encryption\DecryptException;

use App\Http\Requests;

use App\Http\Helpers\UserHelper;

class dashboardController extends Controller
{
    public function index(Request $request)
    {
        //        if(!UserHelper::CheckAuth($request, true))
        //            return redirect()->route('index');
        return view('pages.dashboard');
    }
}
