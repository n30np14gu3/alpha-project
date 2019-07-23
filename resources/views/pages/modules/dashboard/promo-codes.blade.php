<div style="padding:  0 15px">
    <form class="ui form" id="activate-promo-form">
        <div class="field">
            <label>Введите промокод</label>
            <div class="fields">
                <div class="twelve wide field">
                    <input type="text" name="promo-code" placeholder="XXXXXXXX-XXXXXXXX-XXXXXXXX-XXXXXXXX" maxlength="35" required>
                </div>
                <div class="four wide field">
                    <button class="ui fluid button alpha" type="submit">Активировать</button>
                </div>
            </div>
        </div>
        {{ csrf_field() }}
    </form>
    <div class="ui divider"></div>
    <table class="ui unstackable striped selectable table center aligned very compact small fluid">
        <thead>
        <tr>
            <th>Промокод</th>
            <th>Описание</th>
            <th>Это подарок</th>
            <th>Действие</th>
        </tr>
        </thead>
        <tbody>
        @foreach(@$user_data['promo_codes'] as $promo_code)
            <tr id="promo-{{@$promo_code['base']->id}}">
                <td>{{@$promo_code['base']->token}}</td>
                <td>{{@$promo_code['description']}}</td>
                <td>{{@$promo_code['base']->is_gift ? 'Да' : 'Нет'}}</td>
                <td id="promo-{{@$promo_code['base']->id}}-action">
                    @if(@$promo_code['activated'])
                        Уже активирован
                    @else
                        <button class="ui fluid positive small button" onclick="activatePromo({{@$promo_code['base']->id}})">Активировать</button>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>