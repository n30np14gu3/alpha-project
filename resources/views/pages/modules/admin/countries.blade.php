<div class="ui stackable grid">
    <div class="column">
        <div class="row">
            <div class="ui top attached tabular menu">
                <a class="active item" data-tab="t-countries-all">Все</a>
                <a class="item" data-tab="t-countries-management">Управление</a>
            </div>
            <div class="ui bottom attached active tab segment" data-tab="t-countries-all">
                <table class="ui celled striped table very compact center aligned unstackable">
                    <thead>
                    <tr>
                        <th colspan="2">
                            Страны
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(@$staff_data['countries'] as $country)
                        <tr>
                            <td>
                                <i class="flag {{@$country->code}}"></i> {{@$country->title}}
                            </td>
                            <td>
                                {{@$country->code}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="ui bottom attached tab segment" data-tab="t-countries-management">
                <div id="countries-context">
                    <div class="ui secondary menu transparent">
                        <a class="item active" data-tab="t-country-create">Создание</a>
                        <a class="item" data-tab="t-country-edit">Управление</a>
                    </div>
                    <div class="ui tab active" data-tab="t-country-create" style="padding: 15px">
                        <form class="ui form" id="create-county-form">
                            <div class="field">
                                <label>Полное название</label>
                                <input type="text" name="country[title]" placeholder="" required>
                            </div>
                            <div class="field">
                                <label>Код</label>
                                <input type="text" name="country[code]" placeholder="" required>
                            </div>
                            <div class="field">
                                <button type="submit" class="ui alpha button">Создать</button>
                            </div>
                        </form>
                    </div>
                    <div class="ui tab" data-tab="t-country-edit"  style="padding: 15px">
                        <h3>В разработке</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>