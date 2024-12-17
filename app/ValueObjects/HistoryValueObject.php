<?php

namespace App\ValueObjects;

/**
 * Class HistoryValueObject
 * @package App\ValueObjects\HistoryValueObject
 */
class HistoryValueObject
{
    /**
     * @param int $userId
     * @param int $cityId
     * @param float $UVIndex
     * @param int $type
     */
    public function __construct(
        private readonly int $userId,
        private readonly int $cityId,
        private readonly float $UVIndex,
        private readonly int $type
    )
    {}

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'u_id' => $this->userId,
            'city_id' => $this->cityId,
            'value' => $this->UVIndex,
            'type' => $this->type,
        ];
    }
}
