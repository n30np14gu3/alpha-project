<div>
    <table class="ui unstackable striped selectable table center aligned very compact small fluid">
        <thead>
        <tr>
            <th>Дата</th>
            <th>IP Адрес</th>
        </tr>
        </thead>
        <tbody>
        @foreach(@$user_data['login_history'] as $log)
            <tr>
                <td>{{date("d-m-Y H:i:s", $log->date)}}</td>
                <td>{{$log->ip}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>