<?php

namespace Tests\Unit\Models;

use App\Models\City;
use App\Models\History;
use App\Models\User;
use App\Models\UserCity;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

/**
 * Class UserTest
 * @package Tests\Unit\Models
 * @coversDefaultClass \App\Models\User
 */
class UserTest extends TestCase
{
    /** @uses WithFaker */
    use WithFaker;


    /**
     * @test
     * @covers ::cities
     */
    function it_should_check_cities_relation()
    {
        $user = new User();

        $this->assertInstanceOf(BelongsToMany::class, $user->cities());
        $this->assertEquals(City::class, $user->cities()->getRelated()::class);
        $this->assertEquals(UserCity::TABLE_NAME, $user->cities()->getTable());
        $this->assertTrue(in_array('threshold_uv', $user->cities()->getPivotColumns()));
        $this->assertTrue(in_array('threshold_temperature', $user->cities()->getPivotColumns()));
    }

    /**
     * @test
     * @covers ::history
     */
    function it_should_check_history_relation()
    {
        $user = new User();

        $this->assertInstanceOf(HasMany::class, $user->history());
        $this->assertEquals('u_id', $user->history()->getForeignKeyName());
        $this->assertEquals('id', $user->history()->getLocalKeyName());
    }

    /**
     * @test
     * @covers ::getUserHistory
     */
    function it_should_return_user_history()
    {
        $user = User::factory()->make();
        $user->history = History::factory()->count(2)->make()->toArray();
        $data = $user->history;
        $userId = $this->faker->randomDigitNotNull();

        $userMock = Mockery::mock(User::class)->makePartial();

        Auth::shouldReceive('id')->once()->andReturn($userId);

        $userMock->shouldReceive('with')->once()->with('history')->andReturnSelf();
        $userMock->shouldReceive('with')->once()->with('history.city')->andReturnSelf();
        $userMock->shouldReceive('find')->once()->with($userId)->andReturnSelf();
        $userMock->shouldReceive('toArray')->once()->andReturn($user->toArray());

        $this->assertEquals($data, $userMock->getUserHistory());
    }

    /**
     * @test
     * @covers ::isUVThresholdReached
     */
    function it_check_if_uv_threshold_is_reached()
    {
        $user = new User();
        $user->setRawAttributes([
            'pivot_threshold_uv' => 5.0,
        ]);

        $this->setModelOriginal($user, $user->getAttributes());
        $this->assertTrue($user->isUVThresholdReached(5.0));
        $this->assertTrue($user->isUVThresholdReached(6.0));
        $this->assertFalse($user->isUVThresholdReached(4.9));
    }

    /**
     * @test
     * @covers ::isPrecipitationThresholdReached
     */
    function it_check_if_precipitation_threshold_is_reached()
    {
        $user = new User();
        $user->setRawAttributes([
            'pivot_threshold_temperature' => 10.0,
        ]);

        $this->setModelOriginal($user, $user->getAttributes());
        $this->assertTrue($user->isPrecipitationThresholdReached(10.0));
        $this->assertTrue($user->isPrecipitationThresholdReached(15.0));
        $this->assertFalse($user->isPrecipitationThresholdReached(9.9));
    }

    /**
     * @test
     * @covers ::getChatId
     */
    function it_return_chat_id()
    {
        $fields = User::factory()->make()->toArray();
        $user = new User($fields);

        $this->assertEquals($fields['chat_id'], $user->getChatId());
    }

    /**
     * @test
     * @covers ::hasChatId
     */
    function it_check_if_chat_id_not_null()
    {
        $fields = User::factory()->make(['chat_id' => null])->toArray();
        $user = new User($fields);

        $this->assertFalse($user->hasChatId());
    }

    /**
     * @test
     * @covers ::isEmailChannel
     */
    function it_return_true_when_channel_is_email()
    {
        $fields = User::factory()->make(['channel' => 'email'])->toArray();
        $user = new User($fields);

        $this->assertTrue($user->isEmailChannel());
    }

    /**
     * @test
     * @covers ::isTelegramChannel
     */
    function it_return_true_when_channel_is_telegram()
    {
        $fields = User::factory()->make(['channel' => 'telegram'])->toArray();
        $user = new User($fields);

        $this->assertTrue($user->isTelegramChannel());
    }

    /**
     * @test
     * @covers ::setChatId
     */
    function it_should_set_chat_id()
    {
        $chatId = $this->faker->randomDigitNotNull();

        $userMock = Mockery::mock(User::class)->makePartial();

        $userMock->shouldReceive('save')->once();
        $userMock->setChatId($chatId);

        $this->assertEquals($chatId, $userMock->chat_id);
    }

    /**
     * @test
     * @covers ::setChannel
     */
    function it_should_set_channel()
    {
        $channel = $this->faker->word();

        $userMock = Mockery::mock(User::class)->makePartial();

        $userMock->shouldReceive('save')->once();
        $userMock->setChannel($channel);

        $this->assertEquals($channel, $userMock->channel);
    }

    /**
     * @test
     * @covers ::casts
     */
    function it_should_return_casts()
    {
        $expectedCasts = [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
        $user = new User();

        $this->assertEquals(
            $expectedCasts,
            $this->invokeMethod($user, 'casts')
        );
    }
}
