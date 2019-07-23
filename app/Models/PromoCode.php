<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PromoCode
 * @package App\Models
 * @property int id
 * @property int owner_id
 * @property int receiver_id
 * @property int product_id
 * @property int cost_id
 * @property string token
 * @property string sid
 * @property int is_gift
 * @property int creation_time
 */
class PromoCode extends Model
{
    public $timestamps = false;
    protected $table = 'promocodes';
}
