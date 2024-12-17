<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * Class City
 * @package App\Models\City
 */
class City extends Model
{
    /** @uses HasFactory */
    use HasFactory;

    /** @var array $fillable */
    public $fillable = [
        'name',
    ];

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

    /**
     * @param string $cityName
     * @return City
     */
    public function getCityByName(string $cityName): City
    {
        return $this->firstOrCreate(['name' => $cityName]);
    }
}
