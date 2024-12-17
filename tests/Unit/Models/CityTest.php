<?php

namespace Tests\Unit\Models;

use App\Models\City;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Tests\TestCase;

/**
 * Class CityTest
 * @package Tests\Unit\Models
 * @coversDefaultClass \App\Models\City
 */
class CityTest extends TestCase
{
    /** @uses WithFaker */
    use WithFaker;

    /**
     * @test
     * @covers ::users
     */
    function it_should_check_users_relation()
    {
        $city = new City();
        $relationship = $city->users();

        $this->assertInstanceOf(BelongsToMany::class, $relationship);
        $this->assertEquals(User::class, $relationship->getRelated()::class);
        $this->assertEquals('user_cities', $relationship->getTable());

        $pivotColumns = ['threshold_uv', 'threshold_temperature'];

        $this->assertEqualsCanonicalizing($pivotColumns, $relationship->getPivotColumns());
    }

    /**
     * @test
     * @covers ::getCitiesWithAttachedUsers
     */
    function it_should_return_cities_with_users()
    {
        $cities = City::factory()->count(2)->make();
        $cities[0]->users = User::factory()->count(2)->make();
        $cities[1]->users = User::factory()->count(2)->make();

        $cityMock = Mockery::mock(City::class)->makePartial();

        $cityMock->shouldReceive('has')->once()->with('users')->andReturnSelf();
        $cityMock->shouldReceive('with')->once()->with(['users'])->andReturnSelf();
        $cityMock->shouldReceive('get')->once()->with()->andReturn($cities);

        $this->assertEquals($cities, $cityMock->getCitiesWithAttachedUsers());
    }

    /**
     * @test
     * @covers ::getCityByName
     */
    function it_should_return_city_by_its_name()
    {
        $city = City::factory()->make();
        $cityName = $this->faker->city;

        $cityMock = Mockery::mock(City::class)->makePartial();

        $cityMock->shouldReceive('firstOrCreate')->once()->with(['name' => $cityName])->andReturn($city);

        $this->assertEquals($city, $cityMock->getCityByName($cityName));
    }
}
