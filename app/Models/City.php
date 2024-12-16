<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class City
 * @package App\Models\City
 */
class City extends Model
{
    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this
            ->belongsToMany(User::class, UserCity::TABLE_NAME)
            ->withPivot('threshold_uv', 'threshold_temperature');
    }

    /**
     * @return Collection
     */
    public function getCitiesWithAttachedUsers(): Collection
    {
        return $this->has('users')->with(['users'])->get();
    }
}
