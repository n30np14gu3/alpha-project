@extends('pages.forms')
@section('title', ':: Регистрация')
@section('inputs')
    <div class="field">
        <div class="ui left icon input">
            <i class="user icon"></i>
            <input type="email" name="email" placeholder="Введите email">
        </div>
    </div>
    <div class="field">
        <div class="ui left icon input">
            <i class="lock icon"></i>
            <input type="password" maxlength="64" minlength="8" name="password" placeholder="Введите пароль">
        </div>
    </div>
    <div class="field">
        <div class="ui left icon input">
            <i class="lock icon"></i>
            <input type="password" maxlength="64" minlength="8" name="password-2" placeholder="Повторите пароль">
        </div>
    </div>
    <div class="field" style="text-align: left">
        <div class="ui checkbox">
            <input type="checkbox" placeholder="" tabindex="0" class="hidden" name="confirm" required>
            <label>Я принимаю условия <a href="/legal" target="_blank">Пользовательского соглашения</a></label>
        </div>
    </div>
@endsection

@section('after-links')
    <div class="field" style="text-align: left">
        <a href="/form/reset_password" class="ui link">Напомнить пароль</a>
    </div>
    <div class="field" style="text-align: left">
        <a href="/form/login" class="ui link">Авторизация</a>
    </div>
@endsection

@section('submit-text', 'Создать аккаунт')
@section('header-text', 'Регистрация')
@section('form-id', 'register-form')