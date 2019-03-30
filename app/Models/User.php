<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int status
 * @property string email
 * @property false|string reg_date
 */
class User extends Model
{
    public $timestamps = false;
}
