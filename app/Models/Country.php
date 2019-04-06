<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Country
 * @package App\Models
 * @property int id
 * @property string code
 */
class Country extends Model
{
    public $timestamps = false;
    protected $table = 'countries';

}
