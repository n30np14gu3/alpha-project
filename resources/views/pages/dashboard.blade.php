
@extends('index')

@section('main menu')
    <div class="ui large secondary pointing menu">
        <a class="toc item">
            <i class="sidebar icon"></i>
        </a>
        <a class="item" href="/"><h2 id="main-logo">ALPHA | CHEAT</h2></a>
        <div class="right item">
            <a class="item" href="/">HOME</a>
            <a class="item active" href="/exchange">ACCOUNT</a>
            @if(env('BETA_SHOWLANG'))
                <div class="ui selection dropdown transparent">
                    <div class="default text">{{strtoupper(App::getLocale())}} <i class="{{App::getLocale()}} flag"></i></div>
                    <div class="menu">
                        <a class="item" href="/lang/us"><i class="us flag"></i>US</a>
                        <a class="item" href="/lang/ru"><i class="ru flag"></i>RU</a>
                    </div>
                </div>
            @endif
            <a class="item" href="/action/logout">LOGOUT</a>
        </div>
    </div>
@endsection

@section('dashboard')
    <div style="margin: 20px 70px">
        <div class="ui stackable grid">
            <div class="four wide column">
                <div class="ui vertical fluid tabular menu">
                    <div class="item user-personalisation">
                    <span id="avatar"></span>
                    </div>
                    <div class="item">
                        <h2>shockbyte <span class="verify-status info-popup" id="account-status">あ</span> </h2>
                    </div>
                    <div class="item active" data-tab="t-account">@lang('dashboard.tab-account')</div>
                    <div class="item" data-tab="t-security">@lang('dashboard.tab-security')</div>
                    <div class="item" data-tab="t-subscription">@lang('dashboard.tab-subscription')</div>
                    <div class="item" data-tab="t-balance">@lang('dashboard.tab-balance')</div>
                    <div class="item" data-tab="t-promo">@lang('dashboard.tab-promo')</div>
                    <div class="item" data-tab="t-ref">@lang('dashboard.tab-ref')</div>
                    <div class="item" data-tab="t-shop-history">@lang('dashboard.tab-shop-history')</div>
                    <div class="item" data-tab="t-auth-history">@lang('dashboard.tab-auth-history')</div>
                </div>
            </div>
            <div class="twelve wide stretched column">
                <div class="ui tab active" data-tab="t-account">
                    <div class="ui raised segment">
                        @include('pages.modules.dashboard.account')
                    </div>
                </div>
                <div class="ui tab" data-tab="t-security">
                    <div class="ui raised segment">
                        @include('pages.modules.dashboard.security')
                    </div>
                </div>
                <div class="ui tab" data-tab="t-subscription">
                    <div class="ui raised segment">
                        @include('pages.modules.dashboard.subscription')
                    </div>
                </div>
                <div class="ui tab" data-tab="t-balance">
                    <div class="ui raised segment">
                        @include('pages.modules.dashboard.balance')
                    </div>
                </div>
                <div class="ui tab" data-tab="t-promo">
                    <div class="ui raised segment">
                        @include('pages.modules.dashboard.promo')
                    </div>
                </div>
                <div class="ui tab" data-tab="t-shop-history">
                    <div class="ui raised segment">
                        @include('pages.modules.dashboard.shop-history')
                    </div>
                </div>
                <div class="ui tab" data-tab="t-auth-history">
                    <div class="ui raised segment">
                        @include('pages.modules.dashboard.auth-history')
                    </div>
                </div>
                <div class="ui tab" data-tab="t-ref">
                    <div class="ui raised segment">
                        @include('pages.modules.dashboard.ref')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection