<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 * @package App\Models
 * @property int id
 * @property int game_id
 * @property string title
 * @property int status
 * @property int group_id
 */
class Product extends Model
{
    public $timestamps = false;
}
