<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PaymentHistory
 * @package App\Models
 * @property int id
 * @property int user_id
 * @property int product_id
 * @property int cost_id
 * @property double amount
 * @property string description
 * @property int date
 * @property string sign
 */
class PaymentHistory extends Model
{
    public $timestamps = false;
    protected $table = 'payment_history';
}
