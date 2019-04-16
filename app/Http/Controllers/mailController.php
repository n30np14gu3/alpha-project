<?php

namespace App\Http\Controllers;

use App\Http\Helpers\UserHelper;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Helpers\MailHelper;
use App\Models\EmailConfirm;
use App\Models\User;
use App\Models\UserSettings;

class mailController extends Controller
{
    public function confirm(Request $request, $confirm_code)
    {
        $mail = EmailConfirm::where('code', $confirm_code)->get()->first();
        if(!$mail){
            $reason = "Ссылка недействительна";
            return "<b></b>$reason<br><a href='/'>На главную</a>";
        }
        $user = UserHelper::CheckAuth($request, true);
        if(!@$user->id)
            $user = User::where('id', $mail->user_id)->get()->first();

        if($user->id != $mail->user_id){
            $reason = "Ссылка принадлежит другому аккаунту";
            return "<b></b>$reason<br><a href='/'>На главную</a>";
        }

        $user_settings = UserSettings::where('user_id', $user->id)->get()->first();

        if($user_settings->status){
            $reason = "Пользователь уже подтвердил свой аккаунт";
            return "<b></b>$reason<br><a href='/'>На главную</a>";
        }
        if($mail->visited){
            $reason = "Ссылка уже посещена";
            return "<b></b>$reason<br><a href='/'>На главную</a>";
        }
        if($mail->ip != $_SERVER['REMOTE_ADDR']){
            $reason = "Ссылка привязана к другому IP";
            return "<b></b>$reason<br><a href='/'>На главную</a>";
        }

        if((time() - $mail->request_time) > 1200){
            $reason = "Ссылка устарела! На ваш почтовый ящик отправлено новое письмо!";

            $data = [
                'link' => url('/email/confirm/').MailHelper::NewMailConfirmToken($user->id),
                'mail_title' => 'Регистрация на сайте ALPHA CHEAT'
            ];

            MailHelper::SendMail('mail.types.reg_complete', $data, $user->email, 'Подтверждение регистрации :: '.url('/'));
            return "<b></b>$reason<br><a href='/'>На главную</a>";
        }


        $user_settings->status = 1;
        $mail->visited = 1;

        $user_settings->save();
        $mail->save();

        return redirect()->route('dashboard');
    }
}
