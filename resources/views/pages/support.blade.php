@extends('index')
@section('title', ':: Служба поддержки')

@section('main-container')
    @include('pages.modules.default.main-menu')
    <div class="ui raised segment">
        <div class="bold" style="text-align: center">Поддержка</div>
        <table class="ui table  large center aligned striped selectable unstackable">
            <thead>
            <tr>
                <th>#</th>
                <th>Тема</th>
                <th>Дата последнего сообщения</th>
            </tr>
            </thead>
            <tbody>
            @foreach(@$tickets as $ticket)
                <tr onclick="openTicket({{@$ticket['id']}})" style="cursor: pointer" title="Подробнее">
                    <td>{{@$ticket['id']}}</td>
                    <td>{{@$ticket['title']}}</td>
                    <td>{{@$ticket['last_message']}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <br>
        <button class="ui alpha button" onclick="showTicketModal()">Создать тикет</button>
    </div>
@endsection
