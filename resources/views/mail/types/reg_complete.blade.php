@extends('mail.default')

@section('mail-body')
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td>
                <h3 style="text-align: center">Вас приветсвтует команда ALPHA CHEAT</h3>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px 0 30px 0;">
                <p>Вы успешно зарегестрировались на <a href="{{url('/')}}" target="_blank" style="text-decoration: none; color: #ee166c">сайте</a>.</p>
                <p>Для подтверждения Вашей учетной записи, перейдите по следующей <a href="{{$link}}" target="_blank" style="text-decoration: none; color: #ee166c; text-transform: uppercase;">ссылке</a>.</p>
            </td>
        </tr>
    </table>
@endsection