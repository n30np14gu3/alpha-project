<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Ticket
 * @package App\Models
 * @property int id
 * @property int user_id
 * @property int staff_id
 * @property string title
 * @property int creation_time
 * @property bool completed
 */
class Ticket extends Model
{
    public $timestamps = false;
    protected $table = 'tickets';
}
