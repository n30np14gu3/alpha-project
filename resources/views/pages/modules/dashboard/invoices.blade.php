<div>
    <table class="ui unstackable striped selectable table center aligned very compact small fluid">
        <thead>
        <tr>
            <th>ID</th>
            <th>Дата</th>
            <th>Сумма</th>
            <th>Подтверждение</th>
        </tr>
        </thead>
        <tbody>
        @foreach(@$user_data['invoices'] as $invoice)
            <tr id="inv-{{@$invoice->id}}">
                <td>{{@$invoice->token}}</td>
                <td>{{date("d-m-Y H:i:s", @$invoice->time)}}</td>
                <td>{{@$invoice->amount}}</td>
                <td>
                    <button class="ui blue fluid button" onclick="confirmInvoice({{@$invoice->id}})">Подтвердить</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
