<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Balance
 * @package App\Models
 * @poperty int user_id
 * @property int balance
 * @property int total_send
 */
class Balance extends Model
{
    public $timestamps = false;

}
