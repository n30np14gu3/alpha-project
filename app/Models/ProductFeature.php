<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductFeature
 * @package App\Models
 * @property int id
 * @property int product_id
 * @property int module_id
 */
class ProductFeature extends Model
{
    public $timestamps = false;
    protected $table = 'product_features';
    protected $fillable = ['product_id'];
}
