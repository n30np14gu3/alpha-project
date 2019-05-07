<h3 style="text-align: center">Активные блокировки</h3>
<div class="ui stackable grid">
    @foreach(@$user_data['bans']['data'] as $ban)
        <div class="sixteen wide column">
            <div class="row">
                <div class="ui error message">
                    <div class="header">
                        Блокировка от {{@$ban['submit_date']}}
                    </div>
                    <ul class="list">
                        <li>Дата окончания: {{@$ban['end_date']}}</li>
                        <li>Выдал: {{@$ban['staff_nickname']}}</li>
                        <li>Токен (необходим службе поддержки): {{@$ban['token']}}</li>
                    </ul>
                    <br>
                    <b>Причина: </b>
                    <p>{{@$ban['reason']}}</p>
                </div>
            </div>
        </div>
    @endforeach
</div>
