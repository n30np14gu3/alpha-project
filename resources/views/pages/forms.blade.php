@extends('index')

@section('additional-css')
    <style type="text/css">
        .column {
            max-width: 450px;
        }
    </style>
@endsection

@section('main-container')
    @include('pages.modules.default.main-menu')
    <div class="ui middle aligned center aligned grid" style="margin-top: 100px">
        <div class="column">
            <h2>@yield('header-text')</h2>
            <form class="ui form" id="@yield('form-id')">
                <div class="ui segment">
                    @yield('inputs')
                    @if(!env('BETA_DISABLERECAPTCHA'))
                        <div class="field">
                            <div class="g-recaptcha dark" data-sitekey="6Lcn36AUAAAAAODJO5kSQjRi2LE52aieDJBwJ_F-"></div>
                        </div>
                    @endif
                    <div class="field">
                        <button class="ui fluid alpha button" type="submit">@yield('submit-text')</button>
                    </div>
                    @yield('after-links')
                </div>
            </form>
        </div>
    </div>
@endsection