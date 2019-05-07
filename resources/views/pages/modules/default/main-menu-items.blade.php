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
    @if(@$lending)
        <a class="act" href="/form/login">ВХОД</a>
        <a class="act" id="sign-in-button-lending" href="/form/register">РЕГИСТРАЦИЯ</a>
    @else
        <a class="act" id="login-button" href="/form/login">ВХОД</a>
        <a class="act" href="/form/register">РЕГИСТРАЦИЯ</a>
    @endif
@endif
