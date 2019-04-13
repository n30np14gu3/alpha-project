<!DOCTYPE html>
<html lang="{{App::getLocale()}}">
<head>
    <title></title>
    <link rel="stylesheet" href="{{url('/assets/css/semantic.neon.css')}}" type="text/css">
    <link rel="stylesheet" href="{{url('/assets/css/main.css')}}" type="text/css">
</head>
<body>
<div class="ui container">
    <div>
        @yield('mail-body')
    </div>
    <div class="ui divider"></div>
    <p>Это письмо было отправлено автоматически. Пожалуйста, не отвечайте на него.</p>
</div>
</body>
</html>