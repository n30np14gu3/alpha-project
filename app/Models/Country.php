<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class County
 * @package App\Models
 * @property  int id
 * @property  string code
 * @property string title
 */
class Country extends Model
{
    public $timestamps = false;
    protected  $table = 'countries';
}
