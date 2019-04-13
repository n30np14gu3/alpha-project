<div class="ui stackable grid">
    <div class="eleven wide column">
        <div class="row">
            <div class="bold">Данные профиля</div>
            <div class="ui divider"></div>
            <form class="ui form" method="post">
                <div class="two fields">
                    <div class="field">
                        <label>Никнейм</label>
                        <input type="text" name="account[nickname]" placeholder="Никнейм" required>
                    </div>
                    <div class="field">
                        <label>Дата рождения</label>
                        <input type="text" name="account[birthday]" placeholder="Дата рождения" required>
                    </div>
                </div>
                <div class="two fields">
                    <div class="field">
                        <label>Имя</label>
                        <input type="text" name="account[first-name]" placeholder="Имя" required>
                    </div>
                    <div class="field">
                        <label>Почтовый адрес</label>
                        <input type="email" name="account[email]" placeholder="Почтовый адрес" required>
                    </div>
                </div>
                <div class="two fields">
                    <div class="field">
                        <label>Фамилия</label>
                        <input type="text" name="account[last-name]" placeholder="Фамилия" required>
                    </div>
                    <div class="field">
                        <label>Пол</label>
                        <select class="ui fluid dropdown" required>
                            <option value="">Пол</option>
                            <option value="male">Мужской</option>
                            <option value="female">Женский</option>
                        </select>
                    </div>
                </div>
                <div class="field">
                    <label>Steam</label>
                    <div class="fields">
                        <div class="twelve wide field">
                            <input type="text" id="steam-link" placeholder="Ссылка на аккаунт Steam" required>
                        </div>
                        <div class="four wide field">
                            <input class="ui fluid button alpha" type="button" id="verify-account" value="Подтвердить">
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
                https://alpha-cheat.io/invite/code
            </div>
        </div>
    </div>
</div>