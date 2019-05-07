@if(@$logged)
    <div class="ui modal" id="ticket-modal">
        <i class="close icon"></i>
        <div class="header">Новый тикет</div>
        <form class="ui form" style="padding: 30px;" id="ticket-form">
            <div class="field">
                <label>Тема</label>
                <input name="ticket[title]" placeholder="В двух словах опишите проблему" maxlength="100" required>
            </div>
            <div class="field">
                <label>Текст сообщения</label>
                <textarea name="ticket[message]" placeholder="Как можно подробнее расскажите о проблеме" maxlength="250" required></textarea>
            </div>
            @include('pages.modules.default.recaptcha_standalone')
            <div class="field">
                <button type="submit" class="ui alpha button">Отправить запрос в поддержку</button>
            </div>
        </form>
    </div>
@endif
