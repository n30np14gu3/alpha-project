<div class="ui stackable grid">
    <div class="column">
        <div class="row">
            <div class="ui top attached tabular menu">
                <a class="active item" data-tab="t-game-create">Создание</a>
                <a class="item" data-tab="t-game-management">Управление</a>
            </div>
            <div class="ui bottom attached active tab segment" data-tab="t-game-create">
                <div id="games-create-context">
                    <div class="ui secondary menu transparent">
                        <a class="item active" data-tab="t-game-create-game">Игры</a>
                        <a class="item" data-tab="t-game-modules">Модуля</a>
                    </div>
                    <div class="ui tab active" data-tab="t-game-create-game" style="padding: 15px">
                        <form class="ui form" enctype="multipart/form-data" id="new-game-form">
                            <div class="field">
                                <label>Название игры</label>
                                <input type="text" required placeholder="CS GO" name="game-title">
                            </div>
                            <div class="field">
                                <label>Лоадер чита</label>
                                <input type="file" name="game-loader" required>
                            </div>
                            <div class="field">
                                <label>Модули</label>
                                <div class="ui fluid multiple search selection dropdown">
                                    <input type="hidden" name="game-modules" required>
                                    <i class="dropdown icon"></i>
                                    <div class="default text">Хотябы 1</div>
                                    <div class="menu">
                                        @foreach(@$staff_data['game_modules'] as $module)
                                            <div class="item" data-value="{{@$module->id}}">{{@$module->name}}</div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="field">
                                <button type="button" class="ui alpha button" onclick="">Создать</button>
                            </div>
                        </form>
                    </div>
                    <div class="ui tab segment" data-tab="t-game-modules">
                        5
                    </div>
                </div>
            </div>
            <div class="ui bottom attached tab segment" data-tab="t-game-management">
                Second
            </div>
        </div>
    </div>
</div>