<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BalanceCost
 * @package App\Models
 * @property int id
 * @property int amount
 */
class BalanceCost extends Model
{
    public $timestamps = false;
    protected $table = 'balance_costs';
}
