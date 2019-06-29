@extends('pages.forms')
@section('title', ':: Смена email')

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
        <a href="/dashboard" class="ui link">Назад</a>
    </div>
@endsection

@section('submit-text', 'Сменить email')
@section('header-text', 'Смена email')
@section('form-id', 'change-email-form')
