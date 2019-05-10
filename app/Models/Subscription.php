<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Subscription
 * @package App\Models
 * @property int id
 * @property int game_id
 * @property int user_id
 * @property int status
 * @property string activation_date
 * @property string hwid
 */
class Subscription extends Model
{
    public $timestamps = false;
}
