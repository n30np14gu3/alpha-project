<div class="ui stackable grid">
    <div class="nine wide column">
        <div class="row">
            @foreach(@$user_data['subscriptions'] as $subscription)
                <div class="ui styled accordion" style="font-size: 16px; width: 100%">
                    <div class="active title">
                        <i class="steam icon"></i>
                        Подписка на {{@$subscription['game']->name}}
                    </div>
                    <div class="active content" style="padding: 0">
                        <table class="ui unstackable table striped center aligned alpha">
                            <thead>
                            <tr>
                                <th colspan="2">Компоненты подписки</th>
                            </tr>
                            <tr>
                                <th>Имя</th>
                                <th>Дата окончания</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach(@$subscription['modules'] as $subscription_module)
                                <tr>
                                    <td>{{@$subscription_module['name']}}</td>
                                    <td>{{$subscription_module['end_date']}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2" style="padding: 0">
                                    <div class="ui fluid alpha button" onclick="showProductsForm()">Продлить компоненты</div>
                                </td>
                                <td colspan="2" style="padding: 0">
                                    <a class="ui fluid alpha button" href="/download/{{@$subscription['game']->id}}">Скачать лоадер</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
            <br>
            <button type="button" class="ui fluid alpha button" onclick="showProductsForm()">Купить подписку</button>
        </div>
    </div>
    <div class="seven wide column">
        <div class="row">
            @include('pages.modules.dashboard.balance')
        </div>
    </div>
</div>
