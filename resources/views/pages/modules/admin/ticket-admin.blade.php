@extends('index')
@section('title', ':: Служба поддержки')

@section('main-container')
    @include('pages.modules.default.main-menu')
    <div class="ui raised segment">
        @if(@$staff_info['success'])
            <div class="bold" style="text-align: center"><a href="/webmaster" style="color: #2e79a7"><i class="ui angle left icon"></i></a> Поддержка [{{@$messages['ticket_title']}}]</div>
            <div id="ticket-chat">
                @foreach(@$messages['data'] as $message)
                    <div class="ticket-message {{!@$message['is_answer'] ? '' : 'ticket-message__answer'}}">
                        <div>
                            <div class="ticket-message__text">{!! @$message['message'] !!}</div>
                        </div>
                        <div class="ticket-message__info">
                            <div>{{@$message['is_answer'] ? 'Вы' : @$messages['user_nickname']}}</div>
                            <div class="ticket-message__date">{{@$message['time']}}</div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if(!$messages['completed'])
                <form class="ui form" id="admin-ticket-append-form">
                    <div class="field">
                        <label>Ваше сообщение</label>
                        <textarea name="ticket[message]" placeholder="" maxlength="250" required></textarea>
                    </div>
                    <input type="hidden" name="ticket[id]" value="{{@$messages['ticket_id']}}">
                    <div class="inline fields">
                        <div class="field">
                            <button class="ui alpha button" type="submit">Отправить</button>
                        </div>
                        <div class="field">
                            <button class="ui negative button" type="button" onclick="closeTicketStaff({{@$messages['ticket_id']}})">Закрыть тикет</button>
                        </div>
                    </div>
                </form>
            @else
                <div class="ui alpha message">
                    <div class="header">Тикет закрыт</div>
                </div>
            @endif
        @else
            <div class="ui alpha message">
                <div class="header">{{@$staff_info['message']}}</div>
            </div>
        @endif
    </div>
@endsection
