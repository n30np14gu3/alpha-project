<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserInvoice
 * @package App\Models
 * @property int id
 * @property int user_id
 * @property bool active
 * @property int time
 * @property double amount
 * @property int token
 */
class UserInvoice extends Model
{
    public $timestamps = false;
    protected $table = 'user_invoices';
}
