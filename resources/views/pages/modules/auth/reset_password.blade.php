@extends('pages.forms')
@section('title', ':: Восстановление доступа')

@section('inputs')
    <div class="field">
        <div class="ui left icon input">
            <i class="user icon"></i>
            <input type="text" name="email" placeholder="E-mail адрес" required>
        </div>
    </div>
@endsection

@section('after-links')
    <div class="field" style="text-align: left">
        <a href="/form/login" class="ui link">Авторизация</a>
    </div>
    <div class="field" style="text-align: left">
        <a href="/form/register" class="ui link">Регистрация</a>
    </div>
@endsection

@section('submit-text', 'Восстановить пароль')
@section('header-text', 'Сброс пароля')
@section('form-id', 'repass-form')