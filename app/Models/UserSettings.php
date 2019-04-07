<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int user_id
 * @property string nickname
 * @property string fist_name
 * @property string last_name
 * @property int sex
 * @property string birth_date
 * @property int steam_id
 * @property string referral
 */
class UserSettings extends Model
{
    public $timestamps = false;
    protected $table = 'user_settings';

}
