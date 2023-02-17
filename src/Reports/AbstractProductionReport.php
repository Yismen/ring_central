<?php

namespace Dainsys\RingCentral\Reports;

use Illuminate\Support\Arr;
use Dainsys\RingCentral\Models\Call;
use Dainsys\RingCentral\Models\Hour;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Collection;
use Dainsys\RingCentral\Services\CallsService;
use Dainsys\RingCentral\Services\HoursService;

abstract class AbstractProductionReport implements ReportsContract
{
    protected array $fields;
    protected array $dates;
    protected Collection $hours;
    protected Collection $calls;
    protected Collection $filteredCalls;

    /**
     * @param  array $dates
     * @param  array $fields
     * @return mixed
     */
    public function handle(array $dates, array $fields, bool $group_by_date = false)
    {
        $this->dates = $dates;
        $this->fields = $fields;
        $this->filteredCalls = new Collection();

        $this->mergeHoursCalls();
    }

    /**
     * @return bool
     */
    public function hasNewData(): bool
    {
        $hours_sum = $this->hours->sum('total_login_time_mins');
        $calls_summ = $this->hours->sum('total_calls');
        $key = join('-', array_merge(Arr::flatten($this->fields), $this->dates, [$calls_summ, $hours_sum]));

        if (($hours_sum + $calls_summ) === 0 || Cache::has($key)) {
            return false;
        }

        Cache::put($key, $key, 60 * 60 * 24 * 3); // 3 days

        return true;
    }

    public function hours(): Collection
    {
        return $this->hours;
    }

    public function calls(): Collection
    {
        return $this->calls;
    }

    /**
     * Assign hours and calls before merging
     * @return void
     */
    abstract protected function provide(HoursService $hoursService, CallsService $callsService);

    protected function mergeHoursCalls()
    {
        $this->provide(new HoursService(), new CallsService());

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

        $this->calls = $this->filteredCalls;
    }

    protected function sameCampaign(Hour $hour, Call $call): bool
    {
        if ($hour->dial_group_prefix == 'HTL') {
            // Needs to take into consideration inbound and outbound.
            // There are more campaings for HTL group
            return in_array($call->dial_group_prefix, ['HTL', 'AKP', 'BCM']);
        }

        return $hour->dial_group === $call->dial_group;
    }
}
