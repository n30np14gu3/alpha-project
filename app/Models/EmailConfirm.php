<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EmailConfirm
 * @package App\Models
 * @property int id
 * @property int user_id
 * @property int request_time
 * @property string ip
 * @property string code
 * @property int visited
 */
class EmailConfirm extends Model
{
    public $timestamps = false;
    protected $table = "email_confirm";


}
