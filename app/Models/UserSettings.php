<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int user_id
 * @property int status
 * @property false|string reg_date
 * @property string nickname
 * @property string fist_name
 * @property string last_name
 * @property int sex
 * @property string birth_date
 * @property int steam_id
 * @property string referral
 * @property int temp_invoice_id
 */

class UserSettings extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'user_id';
    protected $table = 'user_settings';

}
