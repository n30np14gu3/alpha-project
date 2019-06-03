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

                    @endif
                    @if(@$user_data['base']->staff_status >= 3)

                    @endif

                    @if(@$user_data['base']->staff_status == 4)

                    @endif
                </div>
            </div>

            <div class="twelve wide stretched column">
                <div class="ui tab active" data-tab="t-support-requests">
                    <div class="ui raised segment">

                    </div>
                </div>
                @if(@$user_data['base']->staff_status >= 2)

                @endif
                @if(@$user_data['base']->staff_status >= 3)

                @endif
                @if($user_data['base']->staff_status == 4)

                @endif
            </div>
        </div>
    </div>
@endsection
