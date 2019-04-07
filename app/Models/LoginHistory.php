<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class LoginHistory
 * @package App\Models
 * @property  int user_id
 * @property string date
 * @property string ip
 * @property string info
 */
class LoginHistory extends Model
{
    public $timestamps = false;
    protected $table = 'login_history';
}
