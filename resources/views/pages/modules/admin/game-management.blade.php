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
                        <form class="ui form" enctype="multipart/form-data" id="create-game-form">
                            <div class="field">
                                <label>Название игры</label>
                                <input type="text" placeholder="CS GO" name="game[name]" required>
                            </div>
                            <div class="field">
                                <label>Лоадер чита</label>
                                <input type="file" name="game-loader" required>
                            </div>
                            <div class="field">
                                <label>ZIP Архив с DLL модулями</label>
                                <input type="file" name="game-dll" required>
                            </div>
                            <div class="field">
                                <label>Модули</label>
                                <div class="ui fluid multiple search selection dropdown">
                                    <input type="hidden" name="game[modules]" required>
                                    <i class="dropdown icon"></i>
                                    <div class="default text">Хотябы 1</div>
                                    <div class="menu">
                                        @foreach(@$staff_data['games']['modules'] as $module)
                                            <div class="item" data-value="{{@$module->id}}">{{@$module->name}}</div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="field">
                                <button type="submit" class="ui alpha button" >Создать</button>
                            </div>
                        </form>
                    </div>
                    <div class="ui tab" data-tab="t-game-modules"  style="padding: 15px">
                        <form class="ui form" id="create-module-form">
                            <div class="field">
                                <label>Имя модуля</label>
                                <input type="text" placeholder="" name="game_module[name]" required>
                            </div>
                            <div class="field">
                                <label>Описание</label>
                                <input type="text" placeholder="" name="game_module[description]" required>
                            </div>
                            <div class="field">
                                <button type="submit" class="ui alpha button">Создать</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="ui bottom attached tab segment" data-tab="t-game-management">
                <div id="games-modify-context">
                    <div class="ui secondary menu transparent">
                        <a class="item active" data-tab="t-game-modify-game">Игрой</a>
                        <a class="item" data-tab="t-modify-modules">Модулем</a>
                    </div>
                    <div class="ui tab active" data-tab="t-game-modify-game" style="padding: 15px">
                        <div class="ui search selection dropdown fluid" id="search-game-dropdown">
                            <input type="hidden" id="modify-game-id">
                            <i class="dropdown icon"></i>
                            <div class="default text">Игра</div>
                            <div class="menu">
                                @foreach(@$staff_data['games']['base'] as $game)
                                    <div class="item" data-value="{{@$game->id}}">{{@$game->name}}</div>
                                @endforeach
                            </div>
                        </div>
                        <div class="ui divider"></div>
                        <form class="ui form" enctype="multipart/form-data" id="modify-game-form">
                            <div class="field">
                                <label>Последнее обновление</label>
                                <div class="ui calendar disabled" id="game-last-update">
                                    <div class="ui input left icon">
                                        <i class="calendar icon"></i>
                                        <input type="text" placeholder="" id="game-mod-last-update">
                                    </div>
                                </div>
                            </div>
                            <div class="field">
                                <label>Название игры</label>
                                <input type="text" placeholder="CS GO" name="game[name]" id="game-mod-name">
                            </div>
                            <div class="field">
                                <label>Лоадер чита</label>
                                <input type="file" name="game-loader">
                            </div>
                            <div class="field">
                                <label>ZIP Архив с DLL модулями</label>
                                <input type="file" name="game-dll">
                            </div>
                            <div class="ui checkbox">
                                <input type="checkbox" placeholder="" tabindex="0" class="hidden" name="game[force-update]">
                                <label>Обновить дату при загрузке DLL</label>
                            </div>
                            <div class="field">
                                <label>Модули</label>
                                <div class="ui fluid multiple search selection dropdown" id="game-mod-modules">
                                    <input type="hidden" name="game[modules]">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">Хотябы 1</div>
                                    <div class="menu">
                                        @foreach(@$staff_data['games']['modules'] as $module)
                                            <div class="item" data-value="{{@$module->id}}">{{@$module->name}}</div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="game-mod-game-id" name="game[id]">
                            <div class="inline fields">
                                <div class="field">
                                    <button type="submit" class="ui alpha button">Сохранить</button>
                                </div>
                                <div class="field">
                                    <button type="button" class="ui negative button">Удалить</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="ui tab" data-tab="t-modify-modules"  style="padding: 15px">
                        <h3>В разработке</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>