<?php

namespace Tests\Unit\Models;

use App\Models\History;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Tests\TestCase;

/**
 * Class HistoryTest
 * @package Tests\Unit\Models
 * @coversDefaultClass \App\Models\History
 */
class HistoryTest extends TestCase
{
    /** @uses WithFaker */
    use WithFaker;

    /**
     * @test
     * @covers ::city
     */
    function it_should_check_city_relation()
    {
        $history = new History();

        $this->assertInstanceOf(HasOne::class, $history->city());
        $this->assertEquals('id', $history->city()->getForeignKeyName());
        $this->assertEquals('cities.id', $history->city()->getQualifiedForeignKeyName());
    }

    /**
     * @test
     * @covers ::log
     */
    function it_should_create_log()
    {
        $history = History::factory()->make();
        $data = [];

        $historyMock = Mockery::mock(History::class)->makePartial();

        $historyMock->shouldReceive('create')->once()->with($data)->andReturn($history);

        $this->assertEquals($history, $historyMock->log($data));
    }
}
