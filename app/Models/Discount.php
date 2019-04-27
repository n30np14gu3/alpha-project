<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Discount
 * @package App\Models
 * @property int id;
 * @property int product_id
 * @property bool is_active
 * @property bool is_global
 * @property double percent
 */
class Discount extends Model
{
    public $timestamps = false;
}
