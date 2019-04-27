<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductIncrement
 * @package App\Models
 * @property int id
 * @property  string title
 * @property  int increment
 */
class ProductIncrement extends Model
{
    protected $table = 'increments';
    public $timestamps = false;
}
