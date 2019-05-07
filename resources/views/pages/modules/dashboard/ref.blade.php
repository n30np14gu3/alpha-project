<h2>Реферальная система</h2>
<div class="text container">
    Вы будете получать 10% от суммы с приглашенных Вами пользователей
    <br>
    Ваша реферальная ссылка: <span id="ref-link">{{url('/invite/'.@$user_data['base']->referral_code)}}</span>
</div>
<br>

<div class="bold">Приглашенные пользователи</div>
<div class="ui divider"></div>
<table class="ui unstackable striped selectable table center aligned very compact small fluid">
    <thead>
    <tr>
        <th>Пользователь</th>
        <th>Дата регистрации</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        @foreach(@$user_data['referrals'] as $r)
            <td>{{@$r->nickname}}</td>
            <td>{{date("d-m-Y H:i:s", strtotime(@$r->reg_date))}}</td>
        @endforeach
    </tr>
    </tbody>
</table>
