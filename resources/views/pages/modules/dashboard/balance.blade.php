<div style="padding: 0 15px">
    <h1 id="balance">Ваш баланс: {{@$user_data['balance']}}</h1>
    <div class="ui stackable grid">
        <div class="row">
            <div class="ten wide column">
                <div class="bold">Пополнение кошелька</div>
                <div class="ui divider"></div>
                <div class="ui stackable grid">
                    @foreach(@$user_data['balance_costs'] as $balance_costs)
                        <div class="sixteen wide column">
                            <div class="alert-black">
                                <form method="post" action="/payment">
                                    <div class="ui unstackable equal width grid" style="padding: 0 15px">
                                        <div class="row">
                                            <div class="eleven wide column">
                                                <div class="balance-title">
                                                    Пополнить на <span style="color: #ee166c">{{@$balance_costs[0]}}</span>
                                                </div>
                                            </div>
                                            <div class="five wide column">
                                                <button class="ui fluid button alpha" type="submit">Пополнить</button>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="local_currency" value="{{$balance_costs[0]}}">
                                    <input type="hidden" name="amount" value="{{@$balance_costs[1]}}">
                                    <input type="hidden" name="desc" value="Пополнение баланса на {{@$balance_costs[0]}}">
                                    {{ csrf_field() }}
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="six wide column">
                <div class="bold">Информация</div>
                <div class="ui divider"></div>
                <div class="text container">
                    Пересчет всех средств происходит по курсу Рубль/Евро
                </div>
            </div>
        </div>
    </div>
</div>