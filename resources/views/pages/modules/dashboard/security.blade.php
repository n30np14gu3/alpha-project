<form class="ui form" id="password-form">
    <div class="fields inline">
        <div class="three wide field">
            <label>Текущий пароль:</label>
        </div>
        <div class="twelve wide field">
            <input type="password" name="old-password" maxlength="30" minlength="8" placeholder="" required>
        </div>
    </div>
    <div class="fields inline">
        <div class="three wide field">
            <label>Новый пароль:</label>
        </div>
        <div class="twelve wide field">
            <input type="password" name="new-password" maxlength="30" minlength="8" placeholder="" required>
        </div>
    </div>
    <div class="fields inline">
        <div class="three wide field">
            <label>Повторите пароль:</label>
        </div>
        <div class="twelve wide field">
            <input type="password" name="new-password-2" maxlength="30" minlength="8" placeholder="" required>
        </div>
    </div>
    <div class="field">
        <button class="ui fluid alpha button" type="submit">Сохранить</button>
    </div>
</form>