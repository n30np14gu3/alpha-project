<!DOCTYPE html>
<html lang="{{App::getLocale()}}">
<head>
    <title>ALPHA CHEAT @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{url('/assets/css/semantic.neon.css')}}" type="text/css">
    <link rel="stylesheet" href="{{url('/assets/css/adaptive-menu.css')}}" type="text/css">
    <link rel="stylesheet" href="{{url('/assets/css/main.css')}}" type="text/css">
    <link rel="stylesheet" href="{{url('/assets/css/calendar.css')}}" type="text/css">
    <link rel="stylesheet" href="{{url('/assets/css/toast.css')}}" type="text/css">
    <link rel="stylesheet" href="{{url('/assets/css/tickets.css')}}" type="text/css">
    <script src="{{url('/assets/js/vendor/jquery-3.1.1.min.js')}}" type="text/javascript"></script>
    <script src="https://www.google.com/recaptcha/api.js"async defer></script>
    <script>
        $(document)
            .ready(function() {

                // fix menu when passed
                $('.masthead')
                    .visibility({
                        once: false,
                        onBottomPassed: function() {
                            $('.fixed.menu').transition('fade in');
                        },
                        onBottomPassedReverse: function() {
                            $('.fixed.menu').transition('fade out');
                        }
                    })
                ;

                // create sidebar and attach to menu open
                $('.ui.sidebar').sidebar('attach events', '.toc.item');
            });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    @yield('additional-css')
</head>
<body>
<div id="particles-js"></div>
<div class="ui vertical sidebar menu left">
    <a class="item" href="/">@lang('menu.index')</a>
    <a class="item">@lang('menu.account')</a>
</div>
<div style="min-height: calc(100vh - 100px);">
    <div class="ui container fluid">
        @yield('main-container')
    </div>
</div>
<div class="ui inverted vertical footer segment" style="padding: 50px 15px">
    <div class="ui container">
        <div class="ui stackable inverted divided equal height stackable grid">
            <div class="three wide column">
                <h2 class="main-logo" style="padding: 0">ALPHA | CHEAT</h2>
                <div class="ui inverted link list">
                    <a href="https://t.me/alphacheat" class="item" target="_blank"><i class="ui telegram icon"></i> Telegram</a>
                    <a href="https://vk.com/alphacheat" class="item" target="_blank"><i class="ui vk icon"></i> VK</a>
                </div>
            </div>
            <div class="three wide column">
                <h4 class="ui inverted header">Сервис</h4>
                <div class="ui inverted link list">
                    <a href="/support" class="item" target="_blank">Тех. Поддержка</a>
                    <a href="/legal" class="item" target="_blank">Пользовательское соглашение</a>
                </div>
            </div>
            <div class="seven wide column">
                <p>Copyright © 2019. All Rights Reversed.</p>
            </div>
        </div>
    </div>
</div>
@include('pages.modules.default.modals')
@include('pages.modules.default.analytics')
<script src="{{url('/assets/js/semantic.min.js')}}"></script>
<script src="{{url('/assets/js/vendor/popper.min.js')}}"></script>
<script src="{{url('/assets/js/calendar.js')}}"></script>
<script src="{{url('/assets/js/toast.js')}}"></script>
<script src="{{url('/assets/js/ajax.js')}}" type="text/javascript"></script>
<script src="{{url('/assets/js/vendor/jquery.maskedinput.min.js')}}"></script>
@if(@$user_data['base']->staff_status)
    <script src="{{url('/assets/js/ajax_admin.js')}}" type="text/javascript"></script>
@endif
</body>
</html>