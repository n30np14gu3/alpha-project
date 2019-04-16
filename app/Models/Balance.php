<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Balance
 * @package App\Models
 *
 * @property int user_id
 * @property int balance
 * @property int total_spend
 */
class Balance extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'user_id';

}
