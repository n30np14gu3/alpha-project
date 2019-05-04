<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ApiRequest
 * @package App\Models
 * @property int id
 * @property int user_id
 * @property int session_time
 * @property string session_ip
 * @property string token
 */

class ApiRequest extends Model
{
    public $timestamps = false;
    protected $table = 'api_requests';
}
