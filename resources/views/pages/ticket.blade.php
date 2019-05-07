@extends('index')
@section('title', ':: Служба поддержки')

@section('main-container')
    @include('pages.modules.default.main-menu')
    <div class="ui raised segment">
        <div class="bold" style="text-align: center"><a href="/support" style="color: #2e79a7"><i class="ui angle left icon"></i></a> Поддержка [{{@$messages['ticket_title']}}]</div>
        <div id="ticket-chat">
            @foreach(@$messages['data'] as $message)
                <div class="ticket-message {{@$message['is_answer'] ? '' : 'ticket-message__answer'}}">
                    <div>
                        <div class="ticket-message__text">{!! @$message['message'] !!}</div>
                    </div>
                    <div class="ticket-message__info">
                        <div>{{@!$message['is_answer'] ? 'Вы' : @$messages['staff_nickname']}}</div>
                        <div class="ticket-message__date">{{@$message['time']}}</div>
                    </div>
                </div>
            @endforeach
        </div>
        @if(!$messages['completed'])
            <form class="ui form" id="ticket-append-form">
                <div class="field">
                    <label>Ваше сообщение</label>
                    <textarea name="ticket[message]" placeholder="" maxlength="250" required></textarea>
                </div>
                @include('pages.modules.default.recaptcha_standalone')
                <input type="hidden" name="ticket[id]" value="{{@$messages['ticket_id']}}">
                <div class="inline fields">
                    <div class="field">
                        <button class="ui alpha button" type="submit">Отправить</button>
                    </div>
                    <div class="field">
                        <button class="ui negative button" type="button" onclick="closeTicket({{@$messages['ticket_id']}})">Закрыть тикет</button>
                    </div>
                </div>
            </form>
        @else
            <div class="ui alpha message">
                <div class="header">Тикет закрыт</div>
            </div>
        @endif
    </div>
@endsection
