<div style="padding: 0 15px">
    <h1 id="balance">Ваш баланс: {{@$user_data['balance'][0]}}</h1>
    <div class="ui stackable grid">
        <div class="row">
            <div class="column">
                <form class="ui form" method="post" action="/payment">
                    <div class="field">
                        <label>Введите сумму</label>
                        <div class="fields">
                            <div class="twelve wide field">
                                <input type="number" name="payment[amount]" placeholder="1337">
                            </div>
                            <div class="four wide field">
                                <button class="ui fluid button alpha" type="submit">Пополнить</button>
                            </div>
                        </div>
                    </div>
                    {{ csrf_field() }}
                </form>
            </div>
        </div>
    </div>
</div>