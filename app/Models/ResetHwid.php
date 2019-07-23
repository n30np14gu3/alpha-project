<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ResetHwid
 * @package App\Models
 * @property int id
 * @property int user_id
 * @property int request_time
 * @property string old_hwid
 */
class ResetHwid extends Model
{
    public $timestamps = false;
    protected $table = 'reset_hwid';
}
