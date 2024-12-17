<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class History
 * @package App\Models\History
 */
class History extends Model
{
    /** @var array $fillable */
    protected $fillable = [
        'u_id',
        'city_id',
        'value',
        'type',
    ];

    /**
     * @return HasOne
     */
    public function city(): HasOne
    {
        return $this->hasOne(City::class, 'id', 'city_id');
    }

    /**
     * @param array $data
     * @return History
     */
    public function log(array $data): History
    {
        return $this->create($data);
    }
}
