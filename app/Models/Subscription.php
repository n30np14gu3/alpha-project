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
 * @property int is_lifetime
 * @property string activation_date
 * @property string hwid
 * @property bool hwid_reseted
 */
class Subscription extends Model
{
    public $timestamps = false;
}
