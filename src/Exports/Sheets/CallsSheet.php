<?php

namespace Dainsys\RingCentral\Exports\Sheets;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Illuminate\Database\Eloquent\Collection;
use Dainsys\RingCentral\Services\CallsService;
use Dainsys\RingCentral\Exports\Sheets\Traits\HasNewData;
use Dainsys\RingCentral\Exports\Sheets\Traits\HasCacheKey;
use Dainsys\RingCentral\Exports\Sheets\Handlers\CallsSheetHandler;

class CallsSheet extends AbstractSheet implements SheetsContract, FromView, WithTitle, WithEvents
{
    use HasNewData;
    use HasCacheKey;

    protected Collection $filteredCalls;

    public function view(): \Illuminate\Contracts\View\View
    {
        return view('ring_central::production_report.calls', [
            'title' => 'Calls Summary Report',
            'data' => $this->data,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => new CallsSheetHandler()
        ];
    }

    /**
     * @return string
     */
    public function totalKeyField(): string
    {
        return 'total_calls';
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Calls';
    }

    public function data(): Collection
    {
        $this->data = Cache::remember($this->cacheKey(CallsService::class), 60 * 10, function () {
            $service = new CallsService();
            return $service
                ->datesBetween($this->dates)
                ->groupByDate()
                ->build(Arr::except($this->fields, ['team']))
                ->get();
        });

        return $this->data;
    }
}
