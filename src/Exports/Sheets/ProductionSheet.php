<?php

namespace Dainsys\RingCentral\Exports\Sheets;

use Illuminate\Support\Arr;
use Dainsys\RingCentral\Models\Call;
use Dainsys\RingCentral\Models\Hour;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Illuminate\Database\Eloquent\Collection;
use Dainsys\RingCentral\Services\CallsService;
use Dainsys\RingCentral\Services\HoursService;
use Dainsys\RingCentral\Exports\Sheets\Traits\HasNewData;
use Dainsys\RingCentral\Exports\Sheets\Traits\HasCacheKey;
use Dainsys\RingCentral\Exports\Sheets\Handlers\ProductionSheetHandler;

class ProductionSheet extends AbstractSheet implements SheetsContract, FromView, WithTitle, WithEvents
{
    use HasNewData;
    use HasCacheKey;

    protected Collection $hours;
    protected Collection $calls;
    protected Collection $filteredCalls;

    public function view(): \Illuminate\Contracts\View\View
    {
        return view('ring_central::production_report.hours', [
            'title' => 'Production Report',
            'data' => $this->data,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => new ProductionSheetHandler()
        ];
    }

    /**
     * @return string
     */
    public function totalKeyField(): string
    {
        return 'total_login_time_mins';
    }

    public function data(): Collection
    {
        $this->hours = Cache::remember($this->cacheKey(get_class($this)), 60 * 10, function () {
            $service = new HoursService();
            return $service
            ->datesBetween($this->dates)
            ->groupByDate()
            ->build(Arr::except($this->fields, ['agent_disposition', 'recording_url', 'queue']))
            ->get();
        });

        $this->calls = Cache::remember($this->cacheKey(CallsService::class), 60 * 10, function () {
            $service = new CallsService();
            return $service
                ->datesBetween($this->dates)
                ->groupByDate()
                ->build(Arr::except($this->fields, ['team']))
                ->get();
        });

        $this->mergeHoursCalls();

        $this->data = $this->hours;

        return $this->data;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Production';
    }

    protected function mergeHoursCalls()
    {
        $this->filteredCalls = new Collection();
        $this->hours->map(function ($hour) {
            $calls = $this->calls->filter(function ($call) use ($hour) {
                return $hour->agent_name === $call->agent_name
                    && $hour->date === $call->date
                    && $this->sameCampaign($hour, $call);
            });

            $calls->each(fn ($call) => $this->filteredCalls->push($call));

            $hour->total_duration_mins = $calls->sum('total_duration') / 60 ?? 0;
            $hour->total_on_hold_mins = $calls->sum('total_sec_on_hold') / 60 ?? 0;
            $hour->total_wait_time_mins = $calls->sum('total_agent_wait_time') / 60 ?? 0;
            $hour->total_wrap_time_mins = $calls->sum('total_agent_wrap_time') / 60 ?? 0;
            $hour->total_calls = $calls->sum('total_calls') ?? 0;
            $hour->total_contacts = $calls->sum('total_contacts') ?? 0;
            $hour->total_sales = $calls->sum('total_sales') ?? 0;

            return $hour;
        });
    }

    protected function sameCampaign(Hour $hour, Call $call): bool
    {
        if ($hour->dial_group_prefix == 'HTL') {
            // Needs to take into consideration inbound and outbound.
            // There are more campaings for HTL group
            return in_array($call->dial_group_prefix, ['HTL', 'AKP', 'BCM', 'EMB']);
        }

        return $hour->dial_group === $call->dial_group;
    }
}
