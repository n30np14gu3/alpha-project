<div class="ui large secondary pointing menu">
    <a class="toc item">
        <i class="sidebar icon"></i>
    </a>
    <a class="item" href="/"><h2 class="main-logo">ALPHA | CHEAT</h2></a>
    <div class="right item main-menu">
        <a class="item @yield('main-active')" href="/">ГЛАВНАЯ</a>
        @if(env('BETA_SHOWLANG'))
            <div class="ui selection dropdown transparent item">
                <div class="default text">{{strtoupper(App::getLocale())}} <i class="{{App::getLocale()}} flag" style="margin-left: 6px"></i></div>
                <div class="menu">
                    <a class="item" href="/lang/us"><i class="us flag"></i>US</a>
                    <a class="item" href="/lang/ru"><i class="ru flag"></i>RU</a>
                </div>
            </div>
        @endif
        @if(@$logged)
            <a class="item @yield('dashboard-active')" href="/dashboard">АККАУНТ</a>
            <a class="act" href="/logout">ВЫХОД</a>
        @else
            <a class="act" id="login-button" onclick="showAuthForm()">ВХОД</a>
            <a class="act" onclick="showRegisterForm()">РЕГИСТРАЦИЯ</a>
        @endif
    </div>
</div>