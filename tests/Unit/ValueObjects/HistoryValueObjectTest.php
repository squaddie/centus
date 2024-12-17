<?php

namespace Tests\Unit\ValueObjects;

use App\ValueObjects\HistoryValueObject;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class HistoryValueObjectTest
 * @package Tests\Unit\ValueObjects
 * @coversDefaultClass \App\ValueObjects\HistoryValueObject
 */
class HistoryValueObjectTest extends TestCase
{
    /** @uses WithFaker */
    use WithFaker;

    /** @var int $userId */
    private int $userId;
    /** @var int $cityId */
    private int $cityId;
    /** @var float $UVIndex */
    private float $UVIndex;
    /** @var int $type */
    private int $type;
    /** @var HistoryValueObject $valueObject */
    private HistoryValueObject $valueObject;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->userId = $this->faker->randomDigitNotNull();
        $this->cityId = $this->faker->randomDigitNotNull();
        $this->UVIndex = $this->faker->randomFloat();
        $this->type = $this->faker->numberBetween(1, 2);
        $this->valueObject = new HistoryValueObject(
            $this->userId,
            $this->cityId,
            $this->UVIndex,
            $this->type
        );
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::toArray
     */
    function it_should_return_vo_as_array()
    {
        $expectedResult = [
            'u_id' => $this->userId,
            'city_id' => $this->cityId,
            'value' => $this->UVIndex,
            'type' => $this->type,
        ];

        $this->assertEquals($expectedResult, $this->valueObject->toArray());
    }
}
