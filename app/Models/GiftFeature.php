<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GiftFeature
 * @package App\Models
 * @property int id
 * @property int gift_id
 * @property int module_id
 * @property int increment
 */

class GiftFeature extends Model
{
    public $timestamps = false;
    protected $table = 'gift_features';

}
