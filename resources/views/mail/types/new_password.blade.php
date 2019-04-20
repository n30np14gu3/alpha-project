@extends('mail.default')

@section('mail-body')
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td>
                <h3 style="text-align: center">Вас приветсвтует команда ALPHA CHEAT</h3>
            </td>
        </tr>
        <tr>
            <td>
                <h4 style="text-align: center">Ваши новые данные от аккаунта</h4>
            </td>
        </tr>
        <tr>
            <td>
                <table width="100%">
                    <tr>
                        <td><b>E-mail: </b></td>
                        <td><b style="color: #EE166C">{{@$email}}</b></td>
                    </tr>
                    <tr>
                        <td><b>Пароль: </b></td>
                        <td><b style="color: #EE166C">{{@$password}}</b></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection