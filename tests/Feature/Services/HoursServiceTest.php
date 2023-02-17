<?php

namespace Dainsys\RingCentral\Feature\Services;

use Mockery\MockInterface;
use Illuminate\Support\Arr;
use Dainsys\RingCentral\Models\Hour;
use Dainsys\RingCentral\Tests\TestCase;
use Dainsys\RingCentral\Services\HoursService;

class HoursServiceTest extends TestCase
{
    protected $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new HoursService();
    }

    /** @test */
    public function service_can_be_mocked()
    {
        $this->mock(HoursService::class, function (MockInterface $mock) {
            $mock->shouldReceive('datesBetween')->andReturn($mock);
            $mock->shouldReceive('groupByDate')->andReturn($mock);
            $mock->shouldReceive('build')->once()
                ->andReturn(Hour::query());
        });

        $hours = app(HoursService::class);
        $hours
            ->datesBetween(['2023-01-01'])
            ->groupByDate()
            ->build(Arr::except(['sdfasdf' => 'asfdaf'], 'team'))
        ;
    }
}
