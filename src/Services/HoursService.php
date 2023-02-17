<?php

namespace Dainsys\RingCentral\Services;

use Dainsys\RingCentral\Models\Hour;
use Illuminate\Database\Eloquent\Builder;

class HoursService extends AbstractService
{
    protected function baseQuery(): Builder
    {
        return Hour::query()
            ->selectRaw('SUM(login_time_mins) AS total_login_time_mins
            ,SUM(work_time_mins) AS total_work_time_mins
            ,SUM(talk_time_mins) AS total_talk_time_mins
            ,SUM(off_hook_time_mins) AS total_off_hook_time_mins
            ,SUM(break_time_mins) AS total_break_time_mins
            ,SUM(away_time_mins) AS total_away_time_mins
            ,SUM(lunch_time_mins) AS total_lunch_time_mins
            ,SUM(training_time_mins) AS total_training_time_mins
            ,SUM(pending_disp_time_mins) AS total_pending_disp_time_mins
            ,SUM(time_on_hold_mins) AS total_time_on_hold_mins
            ,SUM(ring_time_mins) AS total_ring_time_mins
            ,SUM(engaged_time_mins) AS total_engaged_time_mins
            ,SUM(rna_time_mins) AS total_rna_time_mins
            ,SUM(available_time_mins) AS total_available_time_mins
            ,SUM(presented) AS total_presented
            ,SUM(accepted) AS total_accepted
            ,SUM(calls_placed_on_hold) AS total_calls_placed_on_hold
            ,SUM(monitoring_sessions) AS total_monitoring_sessions');
    }
}
