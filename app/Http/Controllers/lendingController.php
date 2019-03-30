<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;

class lendingController extends Controller
{
    public function index(){
        return view('pages.main');
    }

    public function referer($refId){
        setcookie('ref_id', $refId, time() + 60*60*24, '/');
        return view('pages.main');
    }
    public function stub(){
        return redirect('/');
    }
}
