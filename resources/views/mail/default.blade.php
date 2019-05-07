<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="{{App::getLocale()}}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>{{@$mail_title}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <style type="text/css">
        a{
            color: white;
        }
    </style>
</head>
<body style="margin: 0; padding: 0; font-family: Helvetica, Arial, sans-serif;">
<table align="center" border="1" cellpadding="0" cellspacing="0" width="600" style="color: white">
    <tr>
        <td align="center" bgcolor="#02061c">
            <img src="{{url('/assets/img/mail-logo.png')}}" alt="ALPHA CHEAT" width="300" height="230" style="display: block;" />
        </td>
    </tr>
    <tr>
        <td bgcolor="#020731" style="padding: 20px 30px; color: white">
            @yield('mail-body')
        </td>
    </tr>
    <tr>
        <td bgcolor="#02061c" style="padding: 30px 0 30px 0">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td width="350" style="padding: 0 0 0 50px">
                        <table>
                            <tr>
                                <td>
                                    <a href="https://t.me/alphacheat" target="_blank" style="text-decoration: none; color: #fff">Telegram</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="https://vk.com/alphacheat" target="_blank" style="text-decoration: none; color: #fff">VK</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="font-size: 14px; color: #828282;" width="350">
                        Это письмо отправлено автоматически, пожалуйста, не отвечайте на него.
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
