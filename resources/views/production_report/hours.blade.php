{{-- "total_login_time_mins" => "138.24000000000001"
"total_work_time_mins" => "100.56999999999999"
"total_talk_time_mins" => "65.890000000000001"
"total_off_hook_time_mins" => "123.93000000000001"
"total_break_time_mins" => "14.279999999999999"
"total_away_time_mins" => "0.0"
"total_lunch_time_mins" => "0.0"
"total_training_time_mins" => "23.379999999999999"
"total_pending_disp_time_mins" => "15.75"
"total_time_on_hold_mins" => "0.0"
"total_ring_time_mins" => "1.3700000000000001"
"total_engaged_time_mins" => "65.849999999999994"
"total_rna_time_mins" => "0.0"
"total_available_time_mins" => "33.18"
"total_presented" => "103.0"
"total_accepted" => "103.0"
"total_calls_placed_on_hold" => "0.0"
"total_monitoring_sessions" => "0.0"
"date" => "2023-02-07"
"dial_group_prefix" => "PUB"
"team" => "ECC-SD"
"dial_group" => "PUB_Clinical_Psych_News_RQ_2023"
"agent_name" => "Arturo Manuel Cerda Rodriguez"
"total_duration_mins" => 65.183333333333
"total_on_hold_mins" => 0
"total_wait_time_mins" => 17.183333333333
"total_wrap_time_mins" => 15.783333333333
"total_calls" => 102
"total_contacts" => 2
"total_sales" => 2 --}}
<h4>{{ $title }}</h4>

<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Team</th>
            <th>Dial Group</th>
            <th>Agent Name</th>
            <th>Login Time Hours</th>
            <th>Work Time Hours</th>
            <th>Talk Time Hours</th>
            <th>Calls</th>
            <th>Contacts</th>
            <th>Sales</th>
            <th>SPH</th>
            <th>Conversion Rate</th>
            <th>Efficiency Rate</th>
            <th>Occupancy Rate</th>
            <th>Avg. Handle Secs</th>
            <th>Avg. Queue Secs</th>
            <th>Avg. Wrap Secs</th>
            <th>Avg. Hold Secs</th>
            <th>Avg. Wait Secs</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $hour)
        <tr>
            <td>{{ $hour->date }}</td>
            <td>{{ $hour->team }}</td>
            <td>{{ $hour->dial_group }}</td>
            <td>{{ $hour->agent_name }}</td>
            <td>{{ $hour->total_login_time_mins / 60 }}</td>
            <td>{{ $hour->total_work_time_mins / 60 }}</td>
            <td>{{ $hour->total_talk_time_mins / 60 }}</td>
            <td>{{ $hour->total_calls }}</td>
            <td>{{ $hour->total_contacts }}</td>
            <td>{{ $hour->total_sales }}</td>
            {{-- SPH --}}
            <td>{{ $hour->total_work_time_mins > 0 ? $hour->total_sales / ($hour->total_work_time_mins / 60) : 0 }}</td>
            {{-- Conversion --}}
            <td>{{ $hour->total_contacts > 0 ? $hour->total_sales / $hour->total_contacts : 0 }}</td>
            {{-- Efficiency --}}
            <td>{{ $hour->total_login_time_mins > 0 ? $hour->total_work_time_mins / $hour->total_login_time_mins : 0 }}
            </td>
            {{-- Occupancy --}}
            <td>{{ $hour->total_work_time_mins > 0 ? ($hour->total_duration_mins + $hour->total_wrap_time_mins) /
                $hour->total_work_time_mins : 0 }}
            </td>
            {{-- Avg. Handle Sec --}}
            <td>{{ $hour->total_calls > 0 ? ($hour->total_duration_mins * 60) / $hour->total_calls : 0 }}</td>
            {{-- Avg. Queue Secs --}}
            <td>{{ $hour->total_calls > 0 ? ($hour->total_wait_time_mins * 60) / $hour->total_calls : 0 }}</td>
            {{-- Avg. Wrap Secs --}}
            <td>{{ $hour->total_calls > 0 ? ($hour->total_wrap_time_mins * 60) / $hour->total_calls : 0 }}</td>
            {{-- Avg. Hold Secs --}}
            <td>{{ $hour->total_calls > 0 ? ($hour->total_on_hold_mins * 60) / $hour->total_calls : 0 }}</td>
            {{-- vg. Wait Secs --}}
            <td>{{ $hour->total_calls > 0 ? ($hour->total_wrap_time_mins * 60) / $hour->total_calls : 0 }}</td>
        </tr>
        @endforeach
    </tbody>
</table>