@extends('mail.default')

@section('mail-body')
    <p style="color: white">
        Здравствуйте, <strong style="color: #ee166c;">{{ @$user_nickname }}</strong>!
    </p>
    <p style="color: white">
        Агент поддержки ответил на Ваш запрос, для просмотра ответа, перейдите по следующей <a href="{{@$link}}" target="_blank" style="text-decoration: none; color: #ee166c; text-transform: uppercase;">ссылке</a>.
    </p>
@endsection