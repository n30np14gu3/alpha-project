<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Game;
use App\Models\GameModule;
use App\Models\Product;
use App\Models\ProductCost;
use App\Models\ProductFeature;
use App\Models\ProductIncrement;
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
        $libs = @$request->file('game-dll');
        $start_count = count($game_modules);

        if(!$game_name || !$start_count || !$loader || !$libs){
            $result['message'] = 'Не все поля заполнены!';
            return json_encode($result);
        }

        $game_modules = GameModule::whereIn('id', $game_modules)->where('game_id', null)->get();

        if(count($game_modules) < $start_count){
            $result['message'] = 'Некоторые Модули принадлежат другим играм!';
            return json_encode($result);
        }

        if(@$loader->getClientMimeType() != "application/x-zip-compressed"){
            $result['message'] = 'Лоадер должен быть в видео архива!';
            return json_encode($result);
        }

        if(@$libs->getClientMimeType() != "application/x-zip-compressed"){
            $result['message'] = 'Библиотеки должны быть в ZIP архиве!';
            return json_encode($result);
        }

        $loader_name = hash("sha256", openssl_random_pseudo_bytes(64).time()).".zip";
        $dll_name = hash("sha256", openssl_random_pseudo_bytes(64).(time() + 64)).".zip";

        if(!$loader->move(storage_path('app/loaders'), $loader_name)){
            $result['message'] = 'Не удалось загрузить лоадер на сервер!';
            return json_encode($result);
        }

        if(!$libs->move(storage_path('app/libs'), $dll_name)){
            $result['message'] = 'Не удалось загрузить библиотеки на сервер!';
            return json_encode($result);
        }


        $game = new Game();
        $game->name = $game_name;
        $game->last_update = time();
        $game->status = 1;
        $game->dll_path = $dll_name;
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
        $game_dll = @$request->file('game-dll');
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
                $result['message'] = 'Лоадер должен быть в видео архива!';
                return json_encode($result);
            }

            if(!$game_loader->move(storage_path('app/loaders'), $game->loader_path)){
                $result['message'] = 'Не удалось загрузить лоадер на сервер!';
                return json_encode($result);
            }
            $game->last_update = time();
        }

        if($game_dll){
            if(@$game_dll->getClientMimeType() != "application/x-zip-compressed"){
                $result['message'] = 'Библиотеки должны быть в ZIP архиве!';
                return json_encode($result);
            }

            if(!$game_dll->move(storage_path('app/libs'), $game->dll_path)){
                $result['message'] = 'Не удалось загрузить библиотеки на сервер!';
                return json_encode($result);
            }

            if(@$request['game']['force-update'])
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

    public function createIncrement(Request $request){
        $result = [
            'status' => 'ERROR',
            'message' => 'Недостаточно прав для выполнения запроса!'
        ];

        $title = @$request['increment']['title'];
        $increment = (int)@$request['increment']['increment'];

        if(!$title || !$increment){
            $result['message'] = 'Не все поля заполнены!';
            return json_encode($result);
        }

        $inc = @ProductIncrement::where('increment', $increment)->get()->first();
        if($inc){
            $result['message'] = 'Инкремент с таким значением уже существует';
            return json_encode($result);
        }

        $inc = new ProductIncrement();
        $inc->increment = $increment;
        $inc->title = $title;
        $inc->save();

        $result['status'] = 'OK';
        return json_encode($result);
    }

    public function createCost(Request $request){
        $result = [
            'status' => 'ERROR',
            'message' => 'Недостаточно прав для выполнения запроса!'
        ];

        $amount = (double)@$request['cost']['amount'];
        $country = @$request['cost']['country'];
        $increment = @$request['cost']['increment'];

        if(!$amount || !$country || !$increment){
            $result['message'] = 'Не все поля заполнены!';
            return json_encode($result);
        }

        $increment = @ProductIncrement::where('id', $increment)->get()->first();
        if(!$increment){
            $result['message'] = 'Такой инкремент не найден!';
            return json_encode($result);
        }

        $country = @Country::where('id', $country)->get()->first();
        if(!$country){
            $result['message'] = 'Такой страны не найдено!';
            return json_encode($result);
        }

        $cost = new ProductCost();
        $cost->cost = $amount;
        $cost->country_id = $country->id;
        $cost->increment_id = $increment->id;
        $cost->save();

        $result['status'] = 'OK';
        return json_encode($result);
    }

    public function createProductFeature(Request $request){
        $result = [
            'status' => 'ERROR',
            'message' => 'Недостаточно прав для выполнения запроса!'
        ];


        $module = @$request['product']['features'];
        if(!$module){
            $result['message'] = 'Не все поля заполнены!';
            return json_encode($result);
        }

        $module = @GameModule::where('id', $module)->get()->first();
        if(!$module){
            $result['message'] = 'Такого модуля не найдено';
        }

        if(count(ProductFeature::where('module_id', $module->id)->get())){
            $result['message'] = 'Этот модуль уже используется';
            return json_encode($result);
        }

        $feature = new ProductFeature();
        $feature->module_id = $module->id;
        $feature->save();

        $result['status'] = 'OK';
        return json_encode($result);
    }

    public function createProduct(Request $request){
        $result = [
            'status' => 'ERROR',
            'message' => 'Недостаточно прав для выполнения запроса!'
        ];

        $title = @$request['product']['title'];
        $game = @$request['product']['game'];
        $costs = explode(',', @$request['product']['costs']);
        $features = explode(',', @$request['product']['features']);
        $costs_count = count($costs);
        $features_count = count($features);

        if(!$title || !$game || !$costs_count || !$features_count){
            $result['message'] = 'Не все поля заполнены!';
            return json_encode($result);
        }

        $costs = ProductCost::whereIn('id', $costs)->where('product_id', null)->get();
        if(count($costs) < $costs_count){
            $result['message'] = 'Некоторые цены принадлежат другим продуктам!';
            return json_encode($result);
        }

        $features = ProductFeature::whereIn('id', $features)->where('product_id', null)->get();
        if(count($features) < $features_count){
            $result['message'] = 'Некоторые компоненты продуктов уже заняты';
            return json_encode($result);
        }

        $game = @Game::where('id', $game)->get()->first();
        if(!$game){
            $result['message'] = 'Игры с таким id не найдено';
            return json_encode($result);
        }

        $product = new Product();
        $product->game_id = $game->id;
        $product->status = 1;
        $product->title = $title;
        $product->save();

        $costs->each(function ($item) use($product){
            $item->update(['product_id' => $product->id]);
        });

        $features->each(function ($iteam) use($product){
           $iteam->update(['product_id', $product->id]);
        });

        $result['status'] = 'OK';
        return json_encode($result);
    }
}
