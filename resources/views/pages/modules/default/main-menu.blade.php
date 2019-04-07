<div class="ui large secondary pointing menu">
    <a class="toc item">
        <i class="sidebar icon"></i>
    </a>
    <a class="item" href="/"><h2 id="main-logo">ALPHA | CHEAT</h2></a>
    <div class="right item main-menu">
        <a class="item active" href="/">ГЛАВНАЯ</a>
        <a class="item" href="/dashboard">АККАУНТ</a>
        @if(env('BETA_SHOWLANG'))
            <div class="ui selection dropdown transparent item">
                <div class="default text">{{strtoupper(App::getLocale())}} <i class="{{App::getLocale()}} flag" style="margin-left: 6px"></i></div>
                <div class="menu">
                    <a class="item" href="/lang/us"><i class="us flag"></i>US</a>
                    <a class="item" href="/lang/ru"><i class="ru flag"></i>RU</a>
                </div>
            </div>
        @endif
        @if(@$auth_complete)
            <a class="act" href="/action/logout">ВЫХОД</a>
        @else
            <a class="act" id="login-button" onclick="showAuthForm()">ВХОД</a>
            <a class="act" onclick="showRegisterForm()">РЕГИСТРАЦИЯ</a>
        @endif
    </div>
</div>