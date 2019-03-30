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
                $('.ui.sidebar')
                    .sidebar('attach events', '.toc.item')
                ;

            });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
</head>
<body>
<div id="particles-js"></div>
<div class="ui vertical sidebar menu left">
    <a class="item" href="/">@lang('menu.index')</a>
    <a class="item">@lang('menu.account')</a>
</div>
<div style="min-height: calc(100vh - 100px);">
    @yield('main menu')
    <div class="ui container fluid">
        @yield('dashboard')
    </div>
</div>
<div class="ui inverted vertical footer segment">
    <div class="ui container">
        <div class="ui stackable inverted divided equal height stackable grid">
            <div class="three wide column">
                <h4 class="ui inverted header">About</h4>
                <div class="ui inverted link list">
                    <a href="#" class="item">Sitemap</a>
                    <a href="#" class="item">Contact Us</a>
                    <a href="#" class="item">Religious Ceremonies</a>
                    <a href="#" class="item">Gazebo Plans</a>
                </div>
            </div>
            <div class="three wide column">
                <h4 class="ui inverted header">Services</h4>
                <div class="ui inverted link list">
                    <a href="#" class="item">Banana Pre-Order</a>
                    <a href="#" class="item">DNA FAQ</a>
                    <a href="#" class="item">How To Access</a>
                    <a href="#" class="item">Favorite X-Men</a>
                </div>
            </div>
            <div class="seven wide column">
                <h4 class="ui inverted header">Footer Header</h4>
                <p>Extra space for a call to action inside the footer that could help re-engage users.</p>
            </div>
        </div>
    </div>
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