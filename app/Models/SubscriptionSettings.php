<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SubscriptionSettings
 * @package App\Models
 * @property int id
 * @property int subscription_id
 * @property int module_id
 * @property int end_date
 */
class SubscriptionSettings extends Model
{
    public $timestamps = false;
    protected $table = 'subscription_settings';
}
