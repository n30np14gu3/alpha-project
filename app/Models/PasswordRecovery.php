<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PasswordRecovery
 * @package App\Models
 * @property int id
 * @property int user_id
 * @property int request_time
 * @property string ip
 * @property string code
 * @property int visited
 */
class PasswordRecovery extends Model
{
    public $timestamps = false;
    protected $table = 'password_recovery';
}
