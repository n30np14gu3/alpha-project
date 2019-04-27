<form class="ui small form">
    <div class="fields inline">
        <label>Промокод:</label>
        <input type="text"  placeholder="XXXXXXXX-XXXXXXXX-XXXXXXXX-XXXXXXXX" maxlength="35" minlength="35">
    </div>
    <div class="field">
        <input type="submit" value="Активировать" class="ui fluid alpha button">
    </div>
    @include('pages.modules.default.recaptcha')
</form>
<table class="ui unstackable striped selectable table center aligned very compact small fluid" style="font-family: BeauSans">
    <thead>
    <tr>
        <th>Промокод</th>
        <th>Дата активации</th>
    </tr>
    </thead>
    <tbody>

    </tbody>
</table>