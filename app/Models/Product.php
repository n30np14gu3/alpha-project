<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 * @property int id
 * @property int game_id
 * @property string game_modules
 * @property string costs
 * @property string title
 * @property int status
 */
class Product extends Model
{
    public $timestamps = false;
}
