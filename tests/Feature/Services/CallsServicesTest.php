<?php

namespace Dainsys\RingCentral\Feature\Services;

use Mockery\MockInterface;
use Illuminate\Support\Arr;
use Dainsys\RingCentral\Models\Call;
use Dainsys\RingCentral\Tests\TestCase;
use Dainsys\RingCentral\Services\CallsService;

class CallsServicesTest extends TestCase
{
    protected $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new CallsService();
    }

    /** @test */
    public function service_can_be_mocked()
    {
        $this->mock(CallsService::class, function (MockInterface $mock) {
            $mock->shouldReceive('datesBetween')->andReturn($mock);
            $mock->shouldReceive('groupByDate')->andReturn($mock);
            $query = Call::query();
            $mock->shouldReceive('build')->once()->andReturn($query);
            // $mock->shouldReceive('get')->once();
        });

        $calls = app(CallsService::class);
        $calls
                ->datesBetween(['2023-01-01'])
                ->groupByDate()
            ->build(Arr::except(['sdfasdf' => 'asfdaf'], 'team'))
            // ->get()
        ;
    }
}
