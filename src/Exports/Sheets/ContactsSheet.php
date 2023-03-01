<?php

namespace Dainsys\RingCentral\Exports\Sheets;

use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Illuminate\Database\Eloquent\Collection;
use Dainsys\RingCentral\Services\CallsService;
use Dainsys\RingCentral\Exports\Sheets\Traits\HasNewData;
use Dainsys\RingCentral\Exports\Sheets\Traits\HasCacheKey;
use Dainsys\RingCentral\Exports\Sheets\Handlers\ContactsSheetHandler;

class ContactsSheet extends AbstractSheet implements SheetsContract, FromView, WithTitle, WithEvents
{
    use HasNewData;
    use HasCacheKey;

    protected Collection $filteredCalls;

    public function view(): \Illuminate\Contracts\View\View
    {
        return view('ring_central::production_report.contacts', [
            'title' => 'Calls Summary Report',
            'data' => $this->data,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => new ContactsSheetHandler()
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
        return 'Contacts';
    }

    public function data(): Collection
    {
        $this->data = Cache::remember($this->cacheKey(CallsService::class . '_contacts'), 60 * 10, function () {
            $service = new CallsService();
            return $service
                ->datesBetween($this->dates)
                ->groupByDate()
                ->build([
                    'dial_group' => $this->dial_groups,
                    'dial_group_prefix' => '%',
                    'agent_name' => '%',
                    'agent_disposition' => '%',
                    'lead_phone' => '%',
                    'recording_url' => '%',
                ])
                ->havingRaw('SUM(contacts) > ?', [0])
                ->get();
        });

        return $this->data;
    }
}
