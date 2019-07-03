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
                            <span id="nickname" class="{{@$user_data['base']->staff_status ? 'is-staff' : ''}} {{@$user_data['settings']->is_partner ? 'is-partner' : ''}}">{{@$user_data['settings']->nickname}}</span>
                            <span class="info-popup verify-status icon {{@$user_data['has_domain'] ? 'active' : ''}}" id="account-status" data-content="{{@$user_data['has_domain'] ? 'Аккаунт подтвержден' : 'Добавь свой Steam аккаунт и получи скидку 3%! (Скидка будет работать, если в вашем нике присутствет ссылка на наш сайт)'}}">あ</span>
                        </h2>
                    </div>
                    @if(@$user_data['bans']['exist'] || !@$user_data['settings']->status)
                        <div class="item active" data-tab="t-bans">Блокировки и ограничения</div>
                    @else
                        <div class="item active" data-tab="t-account">Аккаунт</div>
                        <div class="item" data-tab="t-security">Безопасность</div>
                        <div class="item" data-tab="t-subscription">Подписка</div>
                        <div class="item" data-tab="t-ref">Реферальная система</div>
                        <div class="item" data-tab="t-shop-history">История покупок</div>
                        <div class="item" data-tab="t-invoices">Неподтвержденные счета</div>
                        <div class="item" data-tab="t-auth-history">История входов</div>
                        @if(@$user_data['base']->staff_status >= 1)
                            <a class="item"  href="/webmaster" target="_blank">Панель управления</a>
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
                    <div class="ui tab" data-tab="t-invoices">
                        <div class="ui raised segment">
                            @include('pages.modules.dashboard.invoices')
                        </div>
                    </div>
                    @include('pages.modules.dashboard.games')
                @else
                    <div class="ui active tab" data-tab="t-bans">
                        <div class="ui raised segment">
                            @if(!$user_data['settings']->status)
                                <div class="ui error message">
                                    <div class="header">
                                       Даннный аккаунт не потвержден
                                    </div>
                                    <ul>
                                        <li>Подтвердите свой аккаунт, перейдя по ссылке, отправленной Вам на почту</li>
                                        <li>Для повторной отправки письма на {{@$user_data['base']->email}} перейдите по следующей <a onclick="resendConfirm()" style="color: blue; font-weight: bold; cursor: pointer">ссылке</a></li>
                                        <li>Для смены email перейдите по следующей <a href="/form/change_email" style="color: blue; font-weight: bold;">ссылке</a></li>
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
    @if(!env('APP_DEBUG'))
        <script>
            setTimeout(function() {while (true) {eval("debugger")}});
            let div = document.createElement('div');
            let loop = setInterval(() => {
                console.log(div);
                console.clear();
            });
            Object.defineProperty(div, "id", {get: () => {
                    clearInterval(loop);
                    setTimeout(function() {while (true) {eval("debugger")}});
                    window.location.reload();
                }});
        </script>
    @endif
@endsection
