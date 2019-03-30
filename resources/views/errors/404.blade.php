@extends('errors.base-error')

@section('title')
    <title>@lang('errors.404-title')</title>
@endsection

@section('content')
    <div class="title">@lang('errors.404')</div>
    <a href="/"><h4>@lang('errors.back')</h4></a>
@endsection