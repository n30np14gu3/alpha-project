<div class="ui stackable grid">
    <div class="eleven wide column">
        <div class="row">
            <div class="bold">Данные профиля</div>
            <div class="ui divider"></div>
            <form class="ui form" method="post">
                <div class="two fields">
                    <div class="field">
                        <label>Никнейм</label>
                        <input type="text" name="account[nickname]" placeholder="Никнейм" required value="{{@$user_data['settings']->nickname}}">
                    </div>
                    <div class="field">
                        <label>Дата рождения</label>
                        <input type="text" name="account[birthday]" placeholder="Дата рождения" id="birthday-mask" required value="{{@$user_data['settings']->birth_date}}">
                    </div>
                </div>
                <div class="two fields">
                    <div class="field">
                        <label>Имя</label>
                        <input type="text" name="account[first-name]" placeholder="Имя" required value="{{@$user_data['settings']->first_name}}">
                    </div>
                    <div class="field">
                        <label>Фамилия</label>
                        <input type="text" name="account[last-name]" placeholder="Фамилия" required value="{{@$user_data['settings']->last_name}}">
                    </div>
                </div>
                <div class="two fields">
                    <div class="field">
                        <label>Приглашен</label>
                        <input type="text" placeholder="" required value="{{@$user_data['invitor']}}" disabled>
                    </div>
                    <div class="field">
                        <label>Пол</label>
                        <select class="ui fluid dropdown" required>
                            <option value="{{@$user_data['settings']->sex}}">Пол</option>
                            <option value="0">Мужской</option>
                            <option value="1">Женский</option>
                        </select>
                    </div>
                </div>
                <div class="field">
                    <label>Steam</label>
                    <div class="fields">
                        <div class="twelve wide field">
                            <input type="text" id="steam-link" placeholder="Ссылка на аккаунт Steam" value="{{@$user_data['steam_link']}}" {{@$user_data['has_steam'] ? "disabled" : ''}}>
                        </div>
                        <div class="four wide field">
                            <input class="ui fluid button alpha {{!@$user_data['has_steam'] ? 'info-popup' : ''}}" type="button" id="verify-account" value="Подтвердить" {{@$user_data['has_steam'] ? "disabled" : ''}} data-content='Ссылку на Steam НЕЛЬЗЯ будет изменить!' data-variation='large'>
                        </div>
                    </div>
                </div>
                <input class="ui alpha button" tabindex="0" type="submit" value="Сохранить">
            </form>
        </div>
    </div>
    <div class="five wide column">
        <div class="row">
            <div class="bold">Информация</div>
            <div class="ui divider"></div>
            <div class="text container">
                Пригласи своего друга и получай 10% с каждого его пополнения!
            </div>
            <div style="margin-bottom: 30px"></div>
            <div class="bold">Твоя ссылка</div>
            <div class="ui divider"></div>
            <div class="text container">
                {{url('/invite/'.@$user_data['base']->referral_code)}}
            </div>
        </div>
    </div>
</div>