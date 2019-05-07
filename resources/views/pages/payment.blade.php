@extends('index')

@section('main-container')
    @include('pages.modules.default.main-menu')
    <div style="padding: 30px 30%">
        <form class="ui large form" method="post" action="https://merchant.roboxchange.com/Index.aspx">
            <div class="ui stacked segment">
                <h3 class="ui teal image header">{{@$form_data['description']}}</h3>
                <h5 style="color: #7d7d7d">Внимательно ознакомьтесь с информацией по оплате.</h5>
                <div class="field">
                    <label>К оплате: <span>{{@$form_data['amount_local']}}</span></label>
                </div>
                <div class="field">
                    <label>E-mail для уведомлений: <span>{{@$form_data['email']}}</span></label>
                </div>
                <input type="hidden" name="MrchLogin" value="{{@$form_data['shop_id']}}">
                <input type="hidden" name="OutSum" value="{{@$form_data['amount']}}">
                <input type="hidden" name="InvId" value="{{@$form_data['invoice_id']}}">
                <input type="hidden" name="Desc" value="{{@$form_data['description']}}">
                <input type="hidden" name="SignatureValue" value="{{@$form_data['sign']}}">
                <input type="hidden" name="shp_uid" value="{{@$form_data['user_id']}}">
                <input type="hidden" name="Email" value="{{@$form_data['email']}}">
                @if(env('SHOP_TESTMODE'))
                    <input type="hidden" name="IsTest" value="1">
                @endif
                <div class="two fields">
                    <div class="field">
                        <input type="submit" value="Оплатить" class="ui fluid alpha submit button">
                    </div>
                    <div class="field">
                        <a class="ui fluid negative submit button" href="/dashboard">Отмена</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
