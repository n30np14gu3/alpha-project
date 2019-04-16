@extends('index')
@section('dashboard-active', 'active')

@section('dashboard')
    <div id="dashboard-content">
        <div class="ui stackable grid">
            <div class="four wide column">
                <div class="ui vertical fluid tabular menu">
                    <div class="item user-personalisation">
                    <span id="avatar"></span>
                    </div>
                    <div class="item">
                        <h2>
                            {{@$user_data['settings']->nickname ? @$user_data['settings']->nickname : "NONAME"}}
                            <span class="info-popup verify-status active" id="account-status" data-content="Add users to your feed">あ</span>
                        </h2>
                    </div>
                    @if(@$user_data['settings']->status)
                        <div class="item active" data-tab="t-account">@lang('dashboard.tab-account')</div>
                        <div class="item" data-tab="t-security">@lang('dashboard.tab-security')</div>
                        <div class="item" data-tab="t-subscription">@lang('dashboard.tab-subscription')</div>
                        <div class="item" data-tab="t-balance">@lang('dashboard.tab-balance')</div>
                        <div class="item" data-tab="t-promo">@lang('dashboard.tab-promo')</div>
                        <div class="item" data-tab="t-ref">@lang('dashboard.tab-ref')</div>
                        <div class="item" data-tab="t-shop-history">@lang('dashboard.tab-shop-history')</div>
                        <div class="item" data-tab="t-auth-history">@lang('dashboard.tab-auth-history')</div>
                    @endif
                </div>
            </div>

            <div class="twelve wide stretched column">
                <div class="ui tab active" data-tab="t-account">
                    <div class="ui raised segment">
                        @if(!@$user_data['settings']->status)
                            <div class="ui error message">
                                <div class="header">
                                    Данный аккаунт имеет ограничения
                                </div>
                                <ul class="list">
                                    <li>Вы должны подтвердить свой электронный адрес, чтобы пользоваться услугами проекта</li>
                                </ul>
                            </div>
                        @else
                            @include('pages.modules.dashboard.account')
                        @endif
                    </div>
                </div>
                @if(@$user_data['settings']->status)
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
                @endif
            </div>
        </div>
    </div>
@endsection