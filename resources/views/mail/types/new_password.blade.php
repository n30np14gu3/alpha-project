@extends('mail.default')

@section('mail-body')
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td>
                <h3 style="text-align: center; color: white;">Вас приветсвтует команда ALPHA CHEAT</h3>
            </td>
        </tr>
        <tr>
            <td>
                <h4 style="text-align: center; color: white">Ваши новые данные от аккаунта</h4>
            </td>
        </tr>
        <tr>
            <td>
                <table width="100%">
                    <tr style="color:  white; font-size: 20px">
                        <td style="color: white"><b style="color: white">Пароль: </b></td>
                        <td><b style="color: #EE166C">{{@$password}}</b></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection
