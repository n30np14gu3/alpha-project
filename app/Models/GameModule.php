<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GameModule
 * @package App\Models
 * @property int id
 * @property int game_id
 * @property string name
 * @property string description
 */
class GameModule extends Model
{
    public $timestamps = false;
    protected $table = 'game_modules';
}
