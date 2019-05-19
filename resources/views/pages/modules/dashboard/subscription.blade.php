<div class="ui stackable grid">
    <div class="sixteen wide column">
        <div class="row">
            @include('pages.modules.dashboard.balance')
        </div>
    </div>
    <div class="sixteen wide column">
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
        </div>
    </div>
    <div class="sixteen wide column">
        <div class="row">
            <div class="ui card">
                <div class="content alpha">
                    <h3 style="text-align: center">Оплата подписки</h3>
                </div>
                <div class="content">
                    <form class="ui huge form">
                        <div class="grouped fields">
                            <div class="field">
                                <div class="ui radio checkbox checked">
                                    <input type="radio" name="fruit2" checked="" tabindex="0" class="hidden">
                                    <label>Apples</label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="ui radio checkbox">
                                    <input type="radio" name="fruit2" tabindex="0" class="hidden">
                                    <label>Oranges</label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="ui radio checkbox">
                                    <input type="radio" name="fruit2" tabindex="0" class="hidden">
                                    <label>Pears</label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="ui radio checkbox">
                                    <input type="radio" name="fruit2" tabindex="0" class="hidden">
                                    <label>Grapefruit</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="extra content action">
                    <button class="ui button subscription-pay">1337</button>
                </div>
            </div>
        </div>
    </div>
</div>
