@extends('index')

@section('main-container')
    @include('pages.modules.default.main-menu')
    <div style="margin-top: 30px">
        <div class="ui {{@$style}} message" style="margin: 0 20%">
            <div class="header">{{{@$text}}}</div>
        </div>
    </div>
@endsection