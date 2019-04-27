<?php

namespace App\Http\Controllers;

use App\Http\Helpers\UserHelper;
use App\Models\UserSettings;
use Illuminate\Http\Request;


use App\Models\User;
use App\Models\LoginHistory;

use App\Http\Helpers\CryptoHelper;
use App\Http\Helpers\Geolocation;
use App\Http\Helpers\ReCaptcha;

class actionController extends Controller
{
    /**
     * Non middleware
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(Request $request){
        $request->session()->flush();
        if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                setcookie($name, '', time() - 100, '/');
            }
        }
        return redirect('/');
    }

    /**
     * Non middleware
     * @param Request $request
     * @return false|string
     */
    public function login(Request $request){
        if(UserHelper::CheckAuth($request) != 1)
            return redirect()->route('logout');

        $result = [
          'status' => 'ERROR',
          'message' => 'UNKNOWN ERROR!'
        ];


        $email = @$_POST['email'];
        $password = hash("sha256", @$_POST['password']);
        $remember = @$_POST['save'];

        if(!env('BETA_DISABLERECAPTCHA') && !ReCaptcha::Verify())
        {
            $result['message'] = "Ошибка ReCaptcha!";
            return json_encode($result);
        }

        $user = User::where('email', $email)->where('password', $password)->get()->first();
        if(!$user){
            $result['message'] = "Неверный логин или пароль!";
            return json_encode($result);
        }

        $user_data = UserHelper::CreateUserArray($user->id, $email, $password);
        $user_session = CryptoHelper::EncryptResponse(json_encode($user_data));

        if($remember)
            setcookie('user_session', $user_session, time() + 60*60*24*7, '/');

        setcookie('referral', null, time() - 100, '/');

        $log = new LoginHistory();
        $log->user_id = $user->id;
        $log->ip = $user_data['ip'];
        $log->date = time();
        $log->info = Geolocation::getLocationInfo();
        $log->save();
        $request->session()->put('user_session', $user_session);
        $result['status'] = "OK";
        return json_encode($result);
    }

    /**
     * Non middleware
     * @param Request $request
     * @return false|string
     */
    public function register(Request $request){
        if(UserHelper::CheckAuth($request) != 1)
            return redirect()->route('logout');
        $result = [
            'status' => 'ERROR',
            'message' => 'UNKNOWN ERROR!'
        ];

        if($request->session()->has('user_session')){
            return json_encode($result);
        }

        if(!env('BETA_DISABLERECAPTCHA') && !ReCaptcha::Verify())
        {
            $result['message'] = "Ошибка ReCaptcha!";
            return json_encode($result);
        }

        $email = @$_POST['email'];
        $password = @$_POST['password'];
        $password2 = @$_POST['password-2'];
        $confirm = @$_POST['confirm'];
        $referral = @$_COOKIE['referrer'];

        if(!$email || !$password || !$password2 || !$confirm){
            $result['message']  = "Одно или несколько полей пустые!";
            return json_encode($result);
        }

        if($password != $password2){
            $result['message']  = "Введенные пароли не совпадают!";
            return json_encode($result);
        }


        if(strlen($password) < 8 && strlen($password) > 30){
            $result['message'] = 'Пароль имеет неверную длину';
            return json_encode($result);
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $result['message']  = "Формат почтового ящика неправильный";
            return json_encode($result);
        }

        if(!UserHelper::CreateNewUser($email, $password, $referral)){
            $result['message']  = "Данный email уже зарегестрирован в системе";
            return json_encode($result);
        }

        $result['status'] = "OK";
        return json_encode($result);
    }


    /**
     * Non middleware
     * @param Request $request
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public function resetPassword(Request $request)
    {
        if(UserHelper::CheckAuth($request) != 1)
            return redirect()->route('logout');
        $result = [
            'status' => 'ERROR',
            'message' => 'UNKNOWN ERROR!'
        ];

        if(!env('BETA_DISABLERECAPTCHA') && !ReCaptcha::Verify())
        {
            $result['message'] = "Ошибка ReCaptcha!";
            return json_encode($result);
        }

        $mail = @$_POST['email'];
        if(!$mail){
            $result['message'] = "Вы указали пустую почту";
            return json_encode($result);
        }

        if(!filter_var($mail, FILTER_VALIDATE_EMAIL)){
            $result['message']  = "Формат почтового ящика неправильный";
            return json_encode($result);
        }

        $user = User::where('email', $mail)->get()->first();
        if(!$user){
            $result['message']  = "Данный email не зарегестрирован в системе";
            return json_encode($result);
        }

        UserHelper::ResetPassword($user);
        $result['status'] = "OK";
        return json_encode($result);
    }

    public function verifySteam(Request $request)
    {
        $result = [
            'status' => 'ERROR',
            'message' => 'UNKNOWN ERROR'
        ];

        $link = @$_POST['link'];
        if(!$link){
            $result['message'] = 'Ссылка пустая!';
            return json_encode($result);
        }

        if(strpos($link, 'https://steamcommunity.com') !== 0 || count(explode('/', $link)) != 5){
            $result['message'] = 'Сссылка имеет неверный формат!';
            return json_encode($result);
        }

        $user_data = simplexml_load_file("$link?xml=1", null, LIBXML_NOCDATA);
        if(strpos(@$user_data->steamID, 'alphacheat.com') === false){
            $result['message'] = 'Имя пользователя не содержит имени домена';
            return json_encode($result);
        }

        $user_info = UserHelper::GetLocalUserInfo($request);
        if(!@$user_info['id'])
            return redirect()->route('logout');

        $user = UserSettings::where('user_id', $user_info['id'])->get()->first();
        if(@UserSettings::where('steam_id', $user_data->steamID64)->get()->first()->user_id != 0){
            $result['message'] = 'Данный steam аккаунт уже привязан к другому пользователю.';
            return json_encode($result);
        }
        if($user->steam_id){
            $result['message'] = 'Аккаунт уже привязан к steam';
            return json_encode($result);
        }

        $user->steam_id = $user_data->steamID64;
        $user->save();
        $result['status'] = "OK";
        return json_encode($result);
    }

    public function saveInfo(Request $request){
        $result = [
            'status' => 'ERROR',
            'message' => 'UNKNOWN ERROR'
        ];

        $nickname = @$_POST['account']['nickname'];
        $birthday = @$_POST['account']['birthday'];
        $first_name = @$_POST['account']['first-name'];
        $last_name = @$_POST['account']['last-name'];
        $sex = abs((int)@$_POST['account']['sex']) % 3;

        if(!$nickname || !$first_name || !$last_name || !$sex) {
            $result['message'] = 'Не все поля заполнены!';
            return json_encode($result);
        }

        if(!preg_match('/^[aA-zZ0-9]{1,25}$/', $nickname)) {
            $result['message'] = 'Никнейм имеет неверный формат';
            return json_encode($result);
        }

        if(!strtotime($birthday) && $birthday) {
            $result['message'] = 'Дата рождения имеет неверный формат';
            return json_encode($result);
        }

        if(time() - strtotime($birthday) < 60*60*24*365*14){
            $result['message'] = 'Вам должно быть больше 14 лет!';
            return json_encode($result);
        }

        if(!preg_match('/^[aA-zZ0-9aA-яЯ]{1,25}$/', $first_name)){
            $result['message'] = 'Имя имеет неверный формат';
            return json_encode($result);
        }

        if(!preg_match('/^[aA-zZ0-9aA-яЯ]{1,25}$/', $last_name)){
            $result['message'] = 'Фамилия имеет неверный формат';
            return json_encode($result);
        }

        $user_info = UserHelper::GetLocalUserInfo($request);
        $user_settings = UserSettings::where('user_id', $user_info['id'])->get()->first();
        $user_settings->nickname = $nickname;
        if(!$user_settings->birth_date)
            $user_settings->birth_date = date("Y-m-d H:i:s", strtotime($birthday));
        $user_settings->first_name = $first_name;
        $user_settings->last_name = $last_name;
        $user_settings->sex = $sex;
        $user_settings->save();


        $result['status'] = "OK";
        return json_encode($result);
    }

    public function changePassword(Request $request){
        $result = [
            'status' => 'ERROR',
            'message' => 'UNKNOWN ERROR'
        ];

        $old_password = @$_POST['old-password'];
        $new_password = @$_POST['new-password'];
        $new_password_2 = @$_POST['new-password-2'];

        if(!$old_password || !$new_password || !$new_password_2){
            $result['message'] = 'Не все поля заполнены!';
            return json_encode($result);
        }

        if($new_password_2 != $new_password){
            $result['message'] = 'Введенные пароли не совпадают!';
            return json_encode($result);
        }

        $user_data = UserHelper::GetLocalUserInfo($request);
        if($user_data['password'] != hash("sha256", $old_password)){
            $result['message'] = 'Введен неверный старый пароль!';
            return json_encode($result);
        }

        UserHelper::UpdateUserPassword($request, $new_password);
        $result['status'] = 'OK';
        return json_encode($result);
    }
}
