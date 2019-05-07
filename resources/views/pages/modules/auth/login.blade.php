@extends('pages.forms')
@section('title', ':: Авторизация')
@section('inputs')
    <div class="field">
        <div class="ui left icon input">
            <i class="user icon"></i>
            <input type="text" name="email" placeholder="E-mail адрес" required>
        </div>
    </div>
    <div class="field">
        <div class="ui left icon input">
            <i class="lock icon"></i>
            <input type="password" name="password" placeholder="Пароль" required>
        </div>
    </div>
    <div class="field" style="text-align: left">
        <div class="ui checkbox">
            <input type="checkbox" placeholder="" tabindex="0" class="hidden" name="save">
            <label>Запомнить меня</label>
        </div>
    </div>
@endsection

@section('after-links')
    <div class="field" style="text-align: left">
        <a href="/form/reset_password" class="ui link">Напомнить пароль</a>
    </div>
    <div class="field" style="text-align: left">
        <a href="/form/register" class="ui link">Регистрация</a>
    </div>
@endsection

@section('submit-text', 'Авторизация')
@section('header-text', 'Авторизация')
@section('form-id', 'auth-form')
