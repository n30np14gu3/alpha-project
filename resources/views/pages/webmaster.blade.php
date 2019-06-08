@extends('index')

@section('title', ':: Панель управления')
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
                    <div class="item">
                        <h2>
                            <span id="nickname">{{@$user_data['settings']->nickname}}</span>
                        </h2>
                    </div>
                    <div class="item active" data-tab="t-support-requests">Запросы в службу поддержки</div>
                    @if(@$user_data['base']->staff_status >= 2)
                        <div class="item" data-tab="t-user-management">Управление пользователями</div>
                    @endif
                    @if(@$user_data['base']->staff_status >= 3)
                        <div class="item" data-tab="t-games-management">Управление играми</div>
                        <div class="item" data-tab="t-products-management">Управление продуктами</div>
                    @endif

                    @if(@$user_data['base']->staff_status == 4)
                        <div class="item" data-tab="t-countries">Управление странами</div>
                    @endif
                </div>
            </div>
            <div class="twelve wide stretched column">
                <div class="ui tab active" data-tab="t-support-requests">
                    <div class="ui raised segment">
                        @include('pages.modules.admin.support-requests')
                    </div>
                </div>
                @if(@$user_data['base']->staff_status >= 2)
                    <div class="ui tab" data-tab="t-user-management">
                        <div class="ui raised segment">

                        </div>
                    </div>
                @endif
                @if(@$user_data['base']->staff_status >= 3)
                    <div class="ui tab" data-tab="t-games-management">
                        <div class="ui raised segment">
                            @include('pages.modules.admin.game-management')
                        </div>
                    </div>
                    <div class="ui tab" data-tab="t-products-management">
                        <div class="ui raised segment">
                            @include('pages.modules.admin.products')
                        </div>
                    </div>
                @endif
                @if($user_data['base']->staff_status == 4)
                    <div class="ui tab" data-tab="t-countries">
                        <div class="ui raised segment">
                            @include('pages.modules.admin.countries')
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
