<?php

namespace App\Http\Controllers;

use App\Http\Helpers\UserHelper;
use App\Models\PasswordRecovery;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Helpers\MailHelper;
use App\Models\EmailConfirm;
use App\Models\User;
use App\Models\UserSettings;
use Illuminate\Support\Facades\Password;

class mailController extends Controller
{
    public function confirm(Request $request, $confirm_code)
    {
        $data = [
            'style' => 'error',
            'text' => '',
            'logged' => false
        ];

        $mail = EmailConfirm::where('code', $confirm_code)->get()->first();
        if(!$mail){
            $data['text'] = 'Ссылка недействительна';
            return view('pages.mail', $data);
        }

        $user = UserHelper::CheckAuth($request, true);
        if(!@$user->id)
            $user = User::where('id', $mail->user_id)->get()->first();

        if($user->id != $mail->user_id){
            $data['text'] = 'Ссылка принадлежит другому аккаунту';
            return view('pages.mail', $data);
        }

        $user_settings = UserSettings::where('user_id', $user->id)->get()->first();

        if($user_settings->status){
            $data['text'] = 'Пользователь уже подтвердил свой аккаунт';
            return view('pages.mail', $data);
        }
        if($mail->visited){
            $data['text'] = 'Ссылка уже посещена';
            return view('pages.mail', $data);
        }
        if($mail->ip != $_SERVER['REMOTE_ADDR']){
            $data['text'] = 'Ссылка привязана к другому IP';
            return view('pages.mail', $data);
        }

        if((time() - $mail->request_time) > 1200){

            $mailData = [
                'link' => url('/email/confirm/').MailHelper::NewMailConfirmToken($user->id),
                'mail_title' => 'Регистрация на сайте ALPHA CHEAT'

            ];

            MailHelper::SendMail('mail.types.password_reset', $mailData, $user->email, 'Регистрация на сайте :: '.url('/'));

            $data['text'] = 'Ссылка устарела! На ваш почтовый ящик отправлено новое письмо!';
            return view('pages.mail', $data);
        }

        $user_settings->status = 1;
        $mail->visited = 1;


        $user_settings->save();
        $mail->save();

        $data['text'] = 'Ваш аккаунт успешно подтвержден!';
        $data['style'] = 'success';
        $data['logged'] = UserHelper::CheckAuth($request) == 0;
        return view('pages.mail', $data);
    }

    public function resetPassword(Request $request, $reset_code){
        $data = [
            'style' => 'error',
            'text' => '',
            'logged' => false
        ];

        $mail = PasswordRecovery::where('code', $reset_code)->get()->first();
        if(!$mail){
            $data['text'] = 'Ссылка недействительна';
            return view('pages.mail', $data);
        }

        $user = UserHelper::CheckAuth($request, true);

        if(@$user->id)
            return redirect()->route('logout');

        $user =  User::where('id', $mail->user_id)->get()->first();

        if($user->id != $mail->user_id){
            $data['text'] = 'Ссылка принадлежит другому аккаунту';
            return view('pages.mail', $data);
        }

        if($mail->visited){
            $data['text'] = 'Ссылка уже посещена';
            return view('pages.mail', $data);
        }

        if($mail->ip != $_SERVER['REMOTE_ADDR']){
            $data['text'] = 'Ссылка привязана к другому IP';
            return view('pages.mail', $data);
        }

        if((time() - $mail->request_time) > 1200){

            $data = [
                'link' => url('/email/reset_password/'.MailHelper::NewPasswordRecoveryToken($user->id)),
                'mail_title' => 'Восстановление пароля ALPHA CHEAT'
            ];

            MailHelper::SendMail('mail.types.password_reset', $data, $user->email, 'Восстановление пароля :: '.url('/'));

            $data['text'] = 'Ссылка устарела! На ваш почтовый ящик отправлено новое письмо!';
            return view('pages.mail', $data);
        }

        $new_password = UserHelper::NewPassword(16);
        $user->password = hash("sha256", $new_password);
        $mail->visited = 1;

        $mail->save();
        $user->save();

        $data = [
            'mail_title' => 'Восстановление пароля ALPHA CHEAT',
            'email' => $user->email,
            'password' => $new_password
        ];

        MailHelper::SendMail('mail.types.new_password', $data, $user->email, 'Новый данные от аккаунта :: '.url('/'));

        $data['text'] = 'Новые данные были отправлены Вам на Ваш почтовый адрес!';
        $data['style'] = 'success';
        return view('pages.mail', $data);
    }
}
