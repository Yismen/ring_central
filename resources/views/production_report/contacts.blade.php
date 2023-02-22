{{-- "agent_disposition" => "Disconnected Phone (Agent)"
"total_duration" => "211"
"total_sec_on_hold" => "0"
"total_agent_wait_time" => "13"
"total_agent_wrap_time" => "18"
"total_calls" => "1"
"total_contacts" => "0"
"total_sales" => "0"
"date" => "2023-02-07"
"dial_group_prefix" => "PUB"
"dial_group" => "PUB_Clinical_Psych_News_RQ_2023"
"agent_name" => "Arturo Manuel Cerda Rodriguez"
"recording_url" =>
"https://c02-recordings.virtualacd.biz/api/v1/calls/recordings/?v=1&accountId=12990004&bucket=c02-recordings&region=us-east-1&compliance=false&file=12990004/202302/20/202302201452270132100000082639-1.WAV"
--}}
<h4>{{ $title }}</h4>

<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Dial Group</th>
            <th>Agent Name</th>
            <th>Disposition</th>
            <th>Calls</th>
            <th>Contacts</th>
            <th>Sales</th>
            <th>Avg Duration Secs</th>
            <th>Avg Hold Secs</th>
            <th>Avg Wait Secs</th>
            <th>Avg Wrap Secs</th>
            <th>Recording URL</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $call)
        <tr>
            <td>{{ $call->date }}</td>
            <td>{{ $call->dial_group }}</td>
            <td>{{ $call->agent_name }}</td>
            <td>{{ $call->agent_disposition }}</td>
            <td>{{ $call->total_calls }}</td>
            <td>{{ $call->total_contacts }}</td>
            <td>{{ $call->total_sales }}</td>
            {{-- Avg Duration Secs --}}
            <td>{{ $call->total_calls > 0 ? $call->total_duration / $call->total_calls : 0 }}</td>
            {{-- Avg Hold Secs --}}
            <td>{{ $call->total_calls > 0 ? $call->total_sec_on_hold / $call->total_calls : 0 }}</td>
            {{-- Avg Wait Secs --}}
            <td>{{ $call->total_calls > 0 ? $call->total_agent_wait_time / $call->total_calls : 0 }}</td>
            {{-- Avg Wrap Secs --}}
            <td>{{ $call->total_calls > 0 ? $call->total_agent_wrap_time / $call->total_calls : 0 }}</td>
            <td>{{ $call->recording_url }}</td>
        </tr>
        @endforeach
    </tbody>
</table>