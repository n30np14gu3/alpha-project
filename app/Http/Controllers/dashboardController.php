<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Encryption\EncryptException;
use Illuminate\Contracts\Encryption\DecryptException;

use App\Http\Requests;

class dashboardController extends Controller
{
    public function index()
    {
        return view('pages.dashboard');
    }
}
