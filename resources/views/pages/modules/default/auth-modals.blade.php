<div class="ui mini modal" id="register-modal">
    <i class="close icon"></i>
    <div class="header">Регистрация</div>
    <form class="ui form" style="padding: 30px;" id="register-form">
        <div class="field">
            <label>E-Mail:</label>
            <input type="email" placeholder="example@alphacheat.com" name="email" required>
        </div>
        <div class="field">
            <label>Пароль</label>
            <input type="password" placeholder="" maxlength="64" minlength="8" name="password" required>
        </div>
        <div class="field">
            <label>Повторите пароль</label>
            <input type="password" placeholder="" maxlength="64" minlength="8" name="password-2" required>
        </div>
        <div class="field">
            <div class="ui checkbox">
                <input type="checkbox" placeholder="" tabindex="0" class="hidden" name="confirm" required>
                <label>Я принимаю условия <a href="/legal" target="_blank">Пользовательского соглашения</a></label>
            </div>
        </div>
        @include('pages.modules.default.recaptcha')
        <div class="field">
            <button class="ui alpha button" type="submit">Создать аккаунт</button>
        </div>
    </form>
    <div class="actions">
        <div class="ui link modals" onclick="showAuthForm()">Уже есть аккаунт?</div>
        <div class="ui link modals" onclick="showRepassForm()">Напомнить пароль</div>
    </div>
</div>

<div class="ui mini modal" id="auth-modal">
    <i class="close icon"></i>
    <div class="header">Авторизация</div>
    <form class="ui form" style="padding: 30px;" id="auth-form">
        <div class="field">
            <label>E-Mail:</label>
            <input type="email" placeholder="example@alphacheat.com" name="email" required>
        </div>
        <div class="field">
            <label>Пароль</label>
            <input type="password" placeholder="" maxlength="64" name="password" minlength="8" required>
        </div>
        <div class="field">
            <div class="ui checkbox">
                <input type="checkbox" placeholder="" tabindex="0" class="hidden" name="save">
                <label>Запомнить меня</label>
            </div>
        </div>
        @include('pages.modules.default.recaptcha')
        <div class="field">
            <button class="ui alpha button" type="submit">Вход</button>
        </div>
    </form>
    <div class="actions">
        <div class="ui link modals" onclick="showRepassForm()">Напомнить пароль</div>
        <div class="ui link modals" onclick="showRegisterForm()">Регистрация</div>
    </div>
</div>

<div class="ui mini modal" id="repass-modal">
    <i class="close icon"></i>
    <div class="header">Сброс пароля</div>
    <form class="ui form" style="padding: 30px;" id="repass-form">
        <div class="field">
            <label>E-Mail:</label>
            <input type="email" placeholder="example@alphacheat.com" name="email" required>
        </div>
        @include('pages.modules.default.recaptcha')
        <div class="field">
            <button class="ui alpha button" type="submit">Сбросить пароль</button>
        </div>
    </form>
    <div class="actions">
        <div class="ui link modals" onclick="showAuthForm()">Авторизация</div>
        <div class="ui link modals" onclick="showRegisterForm()">Регистрация</div>
    </div>
</div>