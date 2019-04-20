@extends('index')

@section('payment-form')
    <div style="padding: 30px 30%">
        <form class="ui large form" method="post" action="https://primepayer.com/payment">
            <div class="ui stacked segment">
                <h3 class="ui teal image header">Заголовок</h3>
                <h5 style="color: #7d7d7d">Внимательно ознакомьтесь с информацией по оплате.</h5>
                <div class="field">
                    <label>К оплате: <span>{{@$payment_data['amount_local']}}</span></label>
                </div>
                <div class="field">
                    <label>Email для уведомлений: <span>{{@$payment_data['email']}}</span></label>
                </div>
                <input name="shop" value="{{@$payment_data['form_data']['shop']}}" type="hidden">
                <input name="payment" value="{{@$payment_data['form_data']['payment']}}" type="hidden">
                <input name="amount" value="{{@$payment_data['form_data']['amount']}}" type="hidden">
                <input name="description" value="{{@$payment_data['form_data']['description']}}" type="hidden">
                <input name="currency" value="{{@$payment_data['form_data']['currency']}}" type="hidden">
                <input name="sign" value="{{@$payment_data['sign']}}" type="hidden">
                <input name="uv_uid" value="{{@$payment_data['form_data']['uv_uid']}}" type="hidden">
                <button class="ui fluid alpha submit button" type="submit">Оплатить</button>
            </div>
        </form>
    </div>
@endsection