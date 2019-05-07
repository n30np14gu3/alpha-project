<div class="ui modal" id="products-modal">
    <i class="close icon"></i>
    <div class="header">Оплата подписки</div>
    <form class="ui form" style="padding: 30px;" id="products-form">
        <div id="products-context">
            <div class="ui secondary menu transparent categories">
                @for($i = 0; $i < count(@$products); $i++)
                    <div class="item @if(@$i == 0)  active @endif " data-tab="t-{{@$i}}">{{@$products[$i]['title']}}</div>
                @endfor
            </div>
            @for($i = 0; $i < count(@$products); $i++)
                <div class="ui tab {{@$i == 0 ? 'active' : ''}}" data-tab="t-{{@$i}}">
                    <div class="ui vertical menu products fluid" style="background: transparent">
                        @foreach(@$products[$i]['costs'] as $cost)
                            <div class="item" data-cost="{{@$cost['cid']}}" data-product="{{@$products[@$i]['id']}}">
                                {{@$cost['increment']->title}}
                                <div class="ui teal left pointing label">{{@$cost['cost'][0]}}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endfor
        </div>
        <input id="cost-id" name="cid" type="hidden" required>
        <input id="product-id" name="pid" type="hidden" required>
        <div class="field">
            <button class="ui alpha button fluid" type="submit">Оплатить</button>
        </div>
    </form>
</div>
