<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TicketMessage
 * @package App\Models
 * @property int id
 * @property int ticket_id
 * @property string message
 * @property int time
 * @property string is_answer
 */
class TicketMessage extends Model
{
    public $timestamps = false;
    protected $table = 'ticket_messages';
}
