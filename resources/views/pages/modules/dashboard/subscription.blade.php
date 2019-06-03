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
        <div class="slides">
            <i class="left angle icon"></i>
            <i class="right angle icon"></i>
            <div class="faded slide active">
                <div class="ui equal width stackable grid">
                    <div class="column">
                        <div class="product-container">
                            <div class="ui card">
                                <div class="content">
                                    <div class="ui stackable grid equal width">
                                        <div class="column" style="padding: 0">
                                            <div class="row" style="padding: 0">
                                                <div class="increment-val" style="margin-left: 29px; margin-top: 17px">14</div>
                                            </div>
                                        </div>
                                        <div class="column" style="padding: 0">
                                            <div class="row" style="padding: 0">
                                                <div class="increment-title" style="margin-top: 13px; margin-right: 16px">
                                                    <i class="clock icon"></i><br>
                                                    Дней
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="ui divider alpha" style="margin: 1rem 15px"></div>
                                <div class="content">
                                    <form class="ui huge form">
                                        <div class="grouped fields">
                                            <div class="field">
                                                <div class="ui radio checkbox checked">
                                                    <input type="radio" name="fruit2" checked="" tabindex="0" class="hidden" placeholder="">
                                                    <label>Apples</label>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="fruit2" tabindex="0" class="hidden" placeholder="">
                                                    <label>Oranges</label>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="fruit2" tabindex="0" class="hidden" placeholder="">
                                                    <label>Pears</label>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="ui radio checkbox">
                                                    <input type="radio" name="fruit2" tabindex="0" class="hidden" placeholder="">
                                                    <label>Grapefruit</label>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="extra content action">
                                    <button class="ui button subscription-pay">Оплатить</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="faded slide">
                <h1>Slide two</h1>
                <p>Background image using <code>.inverted</code>, <code>.shadow</code>, and <code>.blurred</code>:</p>
                <p><code>class="inverted shadow blurred image slide"</code></p>
            </div>
            <div class="faded slide">
                <h1>Slide three</h1>
                <p>Background color using <code>.inverted</code>, <code>.salmon</code>, and <code>.faded</code>:</p>
                <p><code>class="inverted faded salmon slide"</code></p>
            </div>
        </div>
    </div>
</div>
