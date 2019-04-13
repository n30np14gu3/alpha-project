@extends('mail.default')

@section('mail-body')
    <h1 style="text-align: center">Вас приветствует команда ALPHA CHEAT</h1>
    <p>Вы успешно зарегестрировались на сайте <a href="{{url('/')}}">{{$_SERVER['HTTP_HOST']}}</a></p>
    <p>Для завершения регистрации и подтверждения Вашего аккаунта, перейдите по <a href="{{@$link}}/">следующей ссылке</a></p>
@endsection