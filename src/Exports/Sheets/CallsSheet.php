<?php

namespace Dainsys\RingCentral\Exports\Sheets;

use Maatwebsite\Excel\Events\AfterSheet;
use Dainsys\RingCentral\Exports\Sheets\Handlers\CallsSheetHandler;

class CallsSheet extends AbstractSheet
{
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view(): \Illuminate\Contracts\View\View
    {
        return view('ring_central::production_report.calls', [
            'title' => 'Calls Summary',
            'data' => $this->data,
        ]);
    }

    public function title(): string
    {
        return 'Calls';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => new CallsSheetHandler()
        ];
    }
}
