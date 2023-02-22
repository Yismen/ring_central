<?php

namespace Dainsys\RingCentral\Exports\Sheets\Traits;

use Illuminate\Support\Arr;

trait HasCacheKey
{
    public function cacheKey(string $class): string
    {
        return join(
            [
                ...(array)str($class)->replace('\\', ' ')->snake()->__toString(),
                ...Arr::flatten($this->dial_groups),
                ...Arr::flatten($this->teams),
                ...$this->dates
            ],
            '_'
        );
    }
}
