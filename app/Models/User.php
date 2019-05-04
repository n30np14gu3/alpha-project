<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property string email
 * @property string password
 * @property string hwid
 * @property string referral_code
 * @property int staff_status
 */
class User extends Model
{
    public $timestamps = false;
}
