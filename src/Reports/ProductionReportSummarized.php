<?php

namespace Dainsys\RingCentral\Reports;

use Illuminate\Support\Arr;
use Dainsys\RingCentral\Services\CallsService;
use Dainsys\RingCentral\Services\HoursService;

class ProductionReportSummarized extends AbstractProductionReport
{
    /**
     * Assign hours and calls before merging
     * @return void
     */
    protected function provide(HoursService $hoursService, CallsService $callsService)
    {
        $this->hours = $hoursService->datesBetween($this->dates)->withoutDateGrouping()->build($this->fields)->get();
        $this->calls = $callsService->datesBetween($this->dates)->withoutDateGrouping()->build(Arr::except($this->fields, 'team'))->get();
    }
}
