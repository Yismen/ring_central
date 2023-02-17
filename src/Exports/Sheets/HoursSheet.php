<?php

namespace Dainsys\RingCentral\Exports\Sheets;

use Maatwebsite\Excel\Events\AfterSheet;
use Dainsys\RingCentral\Exports\Sheets\Handlers\HoursSheetHandler;

class HoursSheet extends AbstractSheet
{
    public function view(): \Illuminate\Contracts\View\View
    {
        return view('ring_central::production_report.hours', [
            'title' => 'Production Report',
            'data' => $this->data,
        ]);
    }

    public function title(): string
    {
        return 'Production';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => new HoursSheetHandler()
        ];
    }
}
