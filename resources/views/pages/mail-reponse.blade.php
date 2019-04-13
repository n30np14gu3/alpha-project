<!DOCTYPE html>
<html lang="{{App::getLocale()}}">
<head>
    <title>@lang('menu.title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{url('/assets/css/semantic.neon.css')}}" type="text/css">
    <link rel="stylesheet" href="{{url('/assets/css/adaptive-menu.css')}}" type="text/css">
    <link rel="stylesheet" href="{{url('/assets/css/main.css')}}" type="text/css">
    <link rel="stylesheet" href="{{url('/assets/css/slider.css')}}" type="text/css">
    <link rel="stylesheet" href="{{url('/assets/css/toast.css')}}" type="text/css">
    <script src="{{url('/assets/js/vendor/jquery-3.1.1.min.js')}}" type="text/javascript"></script>
</head>
<body>
<div class="ui container fluid">

</div>
<script src="{{url('/assets/js/semantic.min.js')}}"></script>
<script src="{{url('/assets/js/slider-semantic.js')}}"></script>
<script src="{{url('/assets/js/toast.js')}}"></script>
<script src="{{url('/assets/js/vendor/popper.min.js')}}"></script>
<script src="{{url('/assets/js/TweenLite.min.js')}}" type="text/javascript"></script>
<script src="{{url('/assets/js/particles.min.js')}}" type="text/javascript"></script>
<script src="{{url('/assets/js/ajax.js')}}" type="text/javascript"></script>
</body>
</html>