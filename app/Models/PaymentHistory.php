<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    public $timestamps = false;
    protected $table = 'payment_history';
}
