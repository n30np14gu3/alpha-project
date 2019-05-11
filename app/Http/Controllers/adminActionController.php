<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Game;
use App\Models\GameModule;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class adminActionController extends Controller
{
    public function createModule(Request $request){
        $result = [
            'status' => 'ERROR',
            'message' => 'Недостаточно прав для выполнения запроса!'
        ];

        $module_name = @$request['game_module']['name'];
        $module_description = @$request['game_module']['description'];

        if(!$module_name || !$module_description){
            $result['message'] = 'Не все поля заполнены';
            return json_encode($result);
        }

        $game_module = new GameModule();
        $game_module->name = $module_name;
        $game_module->description = $module_description;
        $game_module->save();

        $result['status'] = 'OK';
        return json_encode($result);
    }

    public function createGame(Request $request){
        $result = [
            'status' => 'ERROR',
            'message' => 'Недостаточно прав для выполнения запроса!'
        ];

        $game_name = @$request['game']['name'];
        $game_modules = explode(',', @$request['game']['modules']);
        $loader = @$request->file('game-loader');
        $start_count = count($game_modules);
        if(!$game_name || !$start_count || !$loader){
            $result['message'] = 'Не все поля заполнены!';
            return json_encode($result);
        }

        $game_modules = GameModule::whereIn('id', $game_modules)->where('game_id', null)->get();

        if(count($game_modules) < $start_count){
            $result['message'] = 'Некоторые Модули принадлежат другим играм!';
            return json_encode($result);
        }

        if(@$loader->getClientMimeType() != "application/x-zip-compressed"){
            $result['message'] = 'Надо загружать архив!';
            return json_encode($result);
        }

        $loader_name = hash("sha256", openssl_random_pseudo_bytes(64).time()).".zip";
        if(!$loader->move(storage_path('app/loaders'), $loader_name)){
            $result['message'] = 'Не удалось загрузить файл на сервер!';
            return json_encode($result);
        }

        $game = new Game();
        $game->name = $game_name;
        $game->last_update = time();
        $game->status = 1;
        $game->loader_path = $loader_name;
        $game->save();

        $game_modules->each(function ($item) use ($game){
            $item->update(['game_id' => $game->id]);
        });

        $result['status'] = 'OK';
        return json_encode($result);
    }

    public function getGameData(Request $request){
        $result = [
            'status' => 'ERROR',
            'message' => 'UNKNOWN ERROR!',
            'game_name' => '',
            'last_update' => '',
            'game_id' => 0,
            'game_modules' => []
        ];

        $game_id = @$request['game_id'];
        if(!$game_id){
            $result['message'] = 'Поле ID пустое';
            return json_encode($result);
        }

        $game = @Game::where('id', $game_id)->get()->first();
        if(!$game){
            $result['message'] = 'Такая игра не найдена!';
            return json_encode($result);
        }

        $result['status'] = 'OK';
        $result['game_name'] = $game->name;
        $result['game_id'] = $game_id;
        $result['last_update'] = date("M d, Y h:i A", $game->last_update);
        $game_modules = GameModule::all();
        foreach ($game_modules as $module){
            array_push($result['game_modules'], [
                'name' => $module->name,
                'value' => $module->id,
                'selected' => $module->game_id == $game_id
            ]);
        }

        return json_encode($result);
    }

    public function updateGame(Request $request){
        $result = [
            'status' => 'ERROR',
            'message' => 'Недостаточно прав для выполнения запроса!'
        ];

        $game_name = @$request['game']['name'];
        $game_modules = explode(',', @$request['game']['modules']);
        $game_loader = $request->file('game-loader');
        $game_id = @$request['game']['id'];

        $game = @Game::where('id', $game_id)->get()->first();
        if(!$game){
            $result['message'] = 'Игра с таким id не найдена!';
            return json_encode($result);
        }

        if($game_name)
            $game->name = $game_name;

        if($game_loader){
            if(@$game_loader->getClientMimeType() != "application/x-zip-compressed"){
                $result['message'] = 'Надо загружать архив!';
                return json_encode($result);
            }

            if(!$game_loader->move(storage_path('app/loaders'), $game->loader_path)){
                $result['message'] = 'Не удалось загрузить файл на сервер!';
                return json_encode($result);
            }
            $game->last_update = time();
        }

        GameModule::where('game_id', $game_id)->update(['game_id' => null]);
        GameModule::whereIn('id', $game_modules)->update(['game_id' => $game_id]);;
        $game->save();

        $result['status'] = 'OK';
        $result['message'] = date("M d, Y h:i A", $game->last_update);
        return json_encode($result);
    }

    public function createCountry(Request $request){
        $result = [
            'status' => 'ERROR',
            'message' => 'Недостаточно прав для выполнения запроса!'
        ];

        $code = trim(strtolower(@$request['country']['code']));
        $title = trim(@$request['country']['title']);

        if(!$code || !$title){
            $result['message'] = 'Не все поля заполнены!';
            return json_encode($result);
        }

        if(@Country::where('code', $code)->get()->first()){
            $result['message'] = 'Страна с таким кодом уже существует';
            return json_encode($result);
        }

        $country = new Country();
        $country->title = $title;
        $country->code = $code;
        $country->save();

        $result['status'] = 'OK';
        return json_encode($result);
    }

    public function addAllCountries(){
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Country::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $countries = json_decode(file_get_contents(storage_path('/app/support_files/countries.json')));
        foreach($countries as $country){
            $c = new Country();
            $c->code = strtolower(trim($country->cca2));
            $c->title = trim($country->name->official);
            $c->save();
        }

        dd("OK");
    }
}
