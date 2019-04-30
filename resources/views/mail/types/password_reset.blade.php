@extends('mail.default')

@section('mail-body')
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td>
                <h3 style="text-align: center; color: white">Вас приветсвтует команда ALPHA CHEAT</h3>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px 0 30px 0;">
                <h4 style="text-align: center; color: white">Был произведен запрос на сброс пароля</h4>
                <p>Чтобы подтвердить сброс пароля, перейдите по следующей <a href="{{@$link}}" target="_blank" style="text-decoration: none; color: #ee166c; text-transform: uppercase;">ссылке</a>.</p>
            </td>
        </tr>
        <tr>
            <td style="color: #bcbcbc; font-size: 14px">
                <p style="color: white">Если вы не запрашивали сброс пароля, проигнорируйте это письмо!</p>
            </td>
        </tr>
    </table>
@endsection