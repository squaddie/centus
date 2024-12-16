<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class UserCity
 * @package App\Models\UserCity
 * @property float $threshold_uv
 * @property float $threshold_temperature
 */
class UserCity extends Pivot
{
    const TABLE_NAME = 'user_cities';

    /** @var array $fillable */
    public $fillable = [
        'threshold_uv',
        'threshold_temperature'
    ];
}
