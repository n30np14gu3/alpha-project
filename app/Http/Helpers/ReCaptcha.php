<?php
namespace App\Http\Helpers;


class ReCaptcha
{
    public static function Verify()
    {
        $query = 'https://www.google.com/recaptcha/api/siteverify?secret='.env('RECAPTCHA_SECRET').'&response='. @$_POST['g-recaptcha-response'].'&remoteip='.$_SERVER['REMOTE_ADDR'];
        $data = json_decode(file_get_contents($query));
        return (@$data->success == true);
    }
}
