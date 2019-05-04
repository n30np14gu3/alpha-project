<div>
    <table class="ui unstackable striped selectable table center aligned very compact small fluid">
        <thead>
        <tr>
            <th>Описание</th>
            <th>Дата</th>
            <th>Код оплаты</th>
        </tr>
        </thead>
        <tbody>
        @foreach(@$user_data['payment_history'] as $history)
            <tr>
                <td>{{@$history->description}}</td>
                <td>{{date("d-m-Y H:i:s", @$history->date)}}</td>
                <td>{{@$history->sign}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>