<?php

namespace Tests\Unit\Entities;

use App\Entities\WeatherDataEntity;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class WeatherDataEntityTest
 * @package Tests\Unit\Entities
 * @coversDefaultClass \App\Entities\WeatherDataEntity
 */
class WeatherDataEntityTest extends TestCase
{
    /** @uses WithFaker */
    use WithFaker;

    /** @var string $city */
    private string $city;
    /** @var float $temperature */
    private float $temperature;
    /** @var float $precipitation */
    private float $precipitation;
    /** @var float $uvIndex */
    private float $uvIndex;
    /** @var bool $isUVThresholdReached */
    private bool $isUVThresholdReached;
    /** @var bool $isPrecipitationThresholdReached */
    private bool $isPrecipitationThresholdReached;
    /** @var WeatherDataEntity $entity */
    private WeatherDataEntity $entity;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->city = $this->faker->city();
        $this->temperature = $this->faker->randomDigitNotNull();
        $this->precipitation = $this->faker->randomFloat();
        $this->uvIndex = $this->faker->randomFloat();
        $this->isUVThresholdReached = true;
        $this->isPrecipitationThresholdReached = true;
        $this->entity = new WeatherDataEntity(
            $this->city,
            $this->temperature,
            $this->precipitation,
            $this->uvIndex,
            $this->isUVThresholdReached,
            $this->isPrecipitationThresholdReached,
        );
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getCity
     */
    function it_should_return_city()
    {
        $this->assertEquals($this->entity->getCity(), $this->city);
    }

    /**
     * @test
     * @covers ::getTemperature
     */
    function it_should_return_temperature()
    {
        $this->assertEquals($this->entity->getTemperature(), $this->temperature);
    }

    /**
     * @test
     * @covers ::getUVIndex
     */
    function it_should_return_uv_index()
    {
        $this->assertEquals($this->entity->getUVIndex(), $this->uvIndex);
    }

    /**
     * @test
     * @covers ::getPrecipitation
     */
    function it_should_return_precipitation()
    {
        $this->assertEquals($this->entity->getPrecipitation(), $this->precipitation);
    }

    /**
     * @test
     * @covers ::setPrecipitationThresholdReachedFlag
     */
    function it_should_set_precipitation_threshold_reached_flag()
    {
        $this->entity->setPrecipitationThresholdReachedFlag();

        $this->assertTrue($this->isPrecipitationThresholdReached);
    }

    /**
     * @test
     * @covers ::setUVThresholdReachedFlag
     */
    function it_should_set_uv_threshold_reached_flag()
    {
        $this->entity->setUVThresholdReachedFlag();

        $this->assertTrue($this->isUVThresholdReached);
    }

    /**
     * @test
     * @covers ::getIsUVThresholdReachedFlag
     */
    function it_should_return_true_when_uv_threshold_reached_flag_are_set()
    {
        $this->assertEquals($this->entity->getIsUVThresholdReachedFlag(), $this->isUVThresholdReached);
    }

    /**
     * @test
     * @covers ::getIsPrecipitationThresholdReachedFlag
     */
    function it_should_return_true_when_precipitation_reached_flag_are_set()
    {
        $this->assertEquals(
            $this->entity->getIsPrecipitationThresholdReachedFlag(),
            $this->isPrecipitationThresholdReached
        );
    }
}
