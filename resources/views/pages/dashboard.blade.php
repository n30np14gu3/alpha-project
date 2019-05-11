@extends('index')
@section('dashboard-active', 'active')
@section('title', ':: Личный кабинет')
@section('additional-css')
    <style>
        body{
            min-width: 1200px;
            overflow: initial !important;
        }

        body.preview{
            min-width: 1200px;
        }

    </style>
@endsection

@section('main-container')
    @include('pages.modules.default.main-menu')
    <div id="dashboard-content">
        <div class="ui stackable grid">
            <div class="four wide column">
                <div class="ui vertical fluid tabular menu">
                    <div class="item user-personalisation">
                    <span id="avatar"></span>
                    </div>
                    <div class="item">
                        <h2>
                            <span id="nickname">{{@$user_data['settings']->nickname ? @$user_data['settings']->nickname : "NONAME"}}</span>
                            <span class="info-popup verify-status icon {{@$user_data['has_domain'] ? 'active' : ''}}" id="account-status" data-content="{{@$user_data['has_domain'] ? 'Аккаунт подтвержден' : 'Добавь свой Steam аккаунт и получи скидку 3%! (Скидка будет работать, если в вашем нике присутствет ссылка на наш сайт)'}}">あ</span>
                        </h2>
                    </div>
                    @if(@$user_data['bans']['exist'] || !@$user_data['settings']->status)
                        <div class="item active" data-tab="t-bans">Блокировки и ограничения</div>
                    @else
                        <div class="item active" data-tab="t-account">@lang('dashboard.tab-account')</div>
                        <div class="item" data-tab="t-security">@lang('dashboard.tab-security')</div>
                        <div class="item" data-tab="t-subscription">@lang('dashboard.tab-subscription')</div>
                        <div class="item" data-tab="t-promo">@lang('dashboard.tab-promo')</div>
                        <div class="item" data-tab="t-ref">@lang('dashboard.tab-ref')</div>
                        <div class="item" data-tab="t-shop-history">@lang('dashboard.tab-shop-history')</div>
                        <div class="item" data-tab="t-auth-history">@lang('dashboard.tab-auth-history')</div>
                        @if(@$user_data['base']->staff_status >= 1)
                            <div class="item" data-tab="t-support-requests">Запросы в службу поддержки</div>
                            @if(@$user_data['base']->staff_status >= 2)

                            @endif
                            @if(@$user_data['base']->staff_status >= 3)
                                <div class="item" data-tab="t-admin-games">Управление играми</div>
                                <div class="item" data-tab="t-admin-products">Управление товарами</div>
                                <div class="item" data-tab="t-admin-countries">Управление странами</div>
                            @endif
                        @endif
                    @endif
                </div>
            </div>

            <div class="twelve wide stretched column">
                @if(@$user_data['settings']->status)
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
                    @if(@$user_data['base']->staff_status >= 1)
                        <div class="ui tab" data-tab="t-support-requests">
                            <div class="ui raised segment">
                                @include('pages.modules.admin.support-requests')
                            </div>
                        </div>
                        @if(@$user_data['base']->staff_status >= 2)

                        @endif
                        @if(@$user_data['base']->staff_status >= 3)
                            <div class="ui tab" data-tab="t-admin-countries">
                                <div class="ui raised segment">
                                    @include('pages.modules.admin.countries')
                                </div>
                            </div>

                            <div class="ui tab" data-tab="t-admin-games">
                                <div class="ui raised segment">
                                    @include('pages.modules.admin.game-management')
                                </div>
                            </div>
                        @endif
                    @endif
                @else
                    <div class="ui active tab" data-tab="t-bans">
                        <div class="ui raised segment">
                            @if(!$user_data['settings']->status)
                                <div class="ui error message">
                                    <div class="header">
                                       Даннный аккаунт не потвержден
                                    </div>
                                    <ul class="list">
                                        <li>Подтвердите свой аккаунт, перейдя по ссылке, отправленной Вам на почту</li>
                                    </ul>
                                </div>
                            @endif
                            @include('pages.modules.dashboard.bans')
                            <br>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @if(@$user_data['settings']->status)
        @if(!@$user_data['settings']->status)
            <div class="ui error message">
                <div class="header">
                    Данный аккаунт имеет ограничения
                </div>
                <ul class="list">
                    <li>Вы должны подтвердить свой электронный адрес, чтобы пользоваться услугами проекта</li>
                </ul>
            </div>
        @endif
        @include('pages.modules.dashboard.games')
    @endif
@endsection
