<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BalanceFund
 * @package App\Models
 * @property int id
 * @property int country_id
 * @property int amount
 */
class BalanceFund extends Model
{
    public $timestamps = false;
    protected $table = 'balance_funds';
}
