<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameModule extends Model
{
    public $timestamps = false;
    protected $table = 'game_modules';

    //----DB fields
    public $id;
    public $game_id;
    public $name;
    public $description;
    //-----------------------
}
