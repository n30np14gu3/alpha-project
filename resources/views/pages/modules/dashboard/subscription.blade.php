<div class="ui stackable grid">
    <div class="sixteen wide column">
        <div class="row">
            @include('pages.modules.dashboard.balance')
        </div>
    </div>
    <div class="sixteen wide column">
        <div class="row">
            <div style="padding: 0 15px">
                @if(count(@$user_data['subscriptions']) == 0)
                    <div class="ui alpha dark fluid big button" onclick="showProductsForm()">
                        <span class="no-sub">Купить подписку {{@$user_data['balance'][0] == 0 ? '(сначала пополни баланс)' : ''}}</span>
                    </div>
                @else
                    @foreach(@$user_data['subscriptions'] as $subscription)
                        <div class="ui styled accordion" style="font-size: 16px; width: 100%;">
                            <div class="active title">
                                <i class="steam icon"></i>
                                Подписка на {{@$subscription['game']->name}}
                            </div>
                            <div class="active content" style="padding: 0">
                                <table class="ui unstackable table striped center aligned alpha">
                                    <thead>
                                    <tr>
                                        <th colspan="3">Компоненты подписки</th>
                                    </tr>
                                    <tr>
                                        <th>Имя</th>
                                        <th colspan="2">Дата окончания</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach(@$subscription['modules'] as $subscription_module)
                                        <tr>
                                            <td>{{@$subscription_module['name']}}</td>
                                            <td colspan="2">{{@$subscription['base']->is_lifetime ? 'Навсегда' : @$subscription_module['end_date']}}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td style="padding: 0">
                                            <button class="ui fluid alpha button" style="border-radius: 0" onclick="showProductsForm()">Продлить компоненты</button>
                                        </td>
                                        <td style="padding: 0">
                                            <a class="ui fluid positive button" style="border-radius: 0" href="/download/{{@$subscription['game']->id}}">Скачать лоадер</a>
                                        </td>
                                        <td style="padding: 0">
                                            <button
                                                    class="ui fluid negative {{@$subscription['base']->hwid_reseted ? 'disabled' : ''}} button info-popup"
                                                    style="border-radius: 0"
                                                    onclick="resetHwid({{@$subscription['base']->id}})"
                                                    id="hwid-reset-btn"
                                                    data-content='HWID сбрасывается только ОДИН раз!'>Сбросить HWID ({{@$subscription['game']->reset_cost}}₽)</button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                @endif
                <br>
            </div>
        </div>
    </div>
</div>