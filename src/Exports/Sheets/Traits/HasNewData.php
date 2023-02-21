<?php

namespace Dainsys\RingCentral\Exports\Sheets\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

trait HasNewData
{
    public function hasNewData(): bool
    {
        $data_sum = $this->data->sum($this->totalKeyField());

        $key = join(
            '-',
            array_merge(Arr::flatten($this->fields), $this->dates, [$data_sum])
        );

        if ($data_sum === 0 || Cache::has($key)) {
            return false;
        }

        Cache::put($key, $key, 60 * 60 * 24 * 3); // 3 days

        return true;
    }
}
