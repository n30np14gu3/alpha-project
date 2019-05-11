<div class="ui stackable grid">
    <div class="column">
        <div class="row">
            <div class="ui top attached tabular menu">
                <a class="active item" data-tab="t-requests-all">Все</a>
                <a class="item" data-tab="t-requests-accepted">Принятые</a>
            </div>
            <div class="ui bottom attached active tab segment" data-tab="t-requests-all">
                <table class="ui table very compact center aligned striped selectable unstackable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Создал</th>
                        <th>Тема</th>
                        <th>Действие</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(@$staff_data['support_tickets'] as $ticket)
                        @if(!@$ticket['is_empty'] && !@$ticket['is_my'])
                            @continue
                        @endif
                        <tr>
                            <td>{{@$ticket['base']->id}}</td>
                            <td>{{@$ticket['user']->email}}</td>
                            <td>{{@$ticket['base']->title}}</td>
                            @if(@$ticket['is_my'])
                                <td><button class="ui blue button fluid" onclick="openTicketStaff({{@$ticket['base']->id}})">Перейти</button></td>
                            @else
                                <td><button class="ui positive button fluid" onclick="acceptTicket({{@$ticket['base']->id}})">Ответить</button></td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="ui bottom attached tab segment" data-tab="t-requests-accepted">
                <table class="ui table very compact center aligned striped selectable unstackable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Создал</th>
                        <th>Тема</th>
                        <th>Действие</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(@$staff_data['support_tickets'] as $ticket)
                        @if(!@$ticket['is_my'])
                            @continue
                        @endif
                        <tr>
                            <td>{{@$ticket['base']->id}}</td>
                            <td>{{@$ticket['user']->email}}</td>
                            <td>{{@$ticket['base']->title}}</td>
                            <td><button class="ui blue button fluid" onclick="openTicketStaff({{@$ticket['base']->id}})">Перейти</button></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>