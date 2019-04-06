<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Ban
 * @package App\Models
 * @property int user_id
 * @property int is_active
 * @property string reason
 * @property int is_permanent
 * @property int end_date
 */
class Ban extends Model
{
    public $timestamps = false;
    protected $table = 'bans';

}
