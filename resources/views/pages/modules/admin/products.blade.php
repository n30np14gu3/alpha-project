<div class="ui stackable grid">
    <div class="column">
        <div class="row">
            <div class="ui top attached tabular menu">
                <a class="active item" data-tab="t-products">Продукты</a>
                <a class="item" data-tab="t-costs">Цены</a>
                <a class="item" data-tab="t-increments">Инкременты</a>
            </div>
            <div class="ui bottom attached active tab segment" data-tab="t-products"  style="padding: 15px">
                <form class="ui form" id="create-product-form">
                    <div class="field">
                        <label>Название</label>
                        <input type="text" placeholder="" required name="product[title]">
                    </div>
                    <div class="field">
                        <label>Игра</label>
                        <div class="ui fluid search selection dropdown">
                            <input type="hidden" name="product[game]" required>
                            <i class="dropdown icon"></i>
                            <div class="default text"></div>
                            <div class="menu">
                                @foreach(@$staff_data['games']['base'] as $game)
                                    <div class="item" data-value="{{@$game->id}}">{{@$game->name}}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <label>Цены</label>
                        <div class="ui fluid multiple search selection dropdown">
                            <input type="hidden" name="product[costs]" required>
                            <i class="dropdown icon"></i>
                            <div class="default text">Хотябы 1</div>
                            <div class="menu">
                                @foreach(@$staff_data['products']['costs'] as $cost)
                                    <div class="item" data-value="{{@$cost['base']->id}}">{{@$cost['increment_title']}} [{{@$cost['base']->cost}}]</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <label>Модули</label>
                        <div class="ui fluid multiple search selection dropdown">
                            <input type="hidden" name="product[features]" required>
                            <i class="dropdown icon"></i>
                            <div class="default text">Хотябы 1</div>
                            <div class="menu">
                                @foreach(@$staff_data['products']['modules'] as $feature)
                                    <div class="item" data-value="{{@$feature['id']}}">{{@$feature['module_title']}}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <button type="submit" class="ui alpha button">Создать</button>
                    </div>
                </form>
            </div>
            <div class="ui bottom attached tab segment" data-tab="t-costs"  style="padding: 15px">
                <form class="ui form" id="create-cost-form">
                    <div class="field">
                        <label>Цена</label>
                        <input type="text" placeholder="" name="cost[amount]" required>
                    </div>
                    <div class="field">
                        <label>Инкремент</label>
                        <div class="ui fluid search selection dropdown">
                            <input type="hidden" name="cost[increment]" required>
                            <i class="dropdown icon"></i>
                            <div class="default text">Инкремент</div>
                            <div class="menu">
                                @foreach(@$staff_data['products']['increments'] as $increment)
                                    <div class="item" data-value="{{@$increment->id}}">{{@$increment->title}} [{{@$increment->increment}}сек]</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <label>Страна</label>
                        <div class="ui fluid search selection dropdown">
                            <input type="hidden" name="cost[country]" required>
                            <i class="dropdown icon"></i>
                            <div class="default text"></div>
                            <div class="menu">
                                @foreach(@$staff_data['countries'] as $country)
                                    <div class="item" data-value="{{@$country->id}}"><i class="flag {{@$country->code}}"></i>{{@$country->title}}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <button class="ui alpha button" type="submit">Создать</button>
                    </div>
                </form>
            </div>
            <div class="ui bottom attached tab segment" data-tab="t-increments"  style="padding: 15px">
                <form class="ui form" id="create-increment-form">
                    <div class="field">
                        <label>Имя</label>
                        <input type="text" placeholder="" name="increment[title]" required>
                    </div>
                    <div class="field">
                        <label>Увеличение (в секундах)</label>
                        <input type="number" min="1" placeholder="" name="increment[increment]" required>
                    </div>
                    <div class="field">
                        <button type="submit" class="ui alpha button">Создать</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>