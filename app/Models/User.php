<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property string email
 * @property string password
 * @property int status
 * @property false|string reg_date
 * @property string referral_code
 */
class User extends Model
{
    public $timestamps = false;
}
