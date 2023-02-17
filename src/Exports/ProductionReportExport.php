<?php

namespace Dainsys\RingCentral\Exports;

use Illuminate\Database\Eloquent\Collection;
use Dainsys\RingCentral\Exports\Sheets\CallsSheet;
use Dainsys\RingCentral\Exports\Sheets\HoursSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProductionReportExport implements WithMultipleSheets
{
    public $hours;
    public $calls;

    public function __construct(Collection $hours, Collection $calls)
    {
        $this->hours = $hours;
        $this->calls = $calls;
    }

    public function sheets(): array
    {
        return [
            new HoursSheet($this->hours),
            new CallsSheet($this->calls),
        ];
    }
}
