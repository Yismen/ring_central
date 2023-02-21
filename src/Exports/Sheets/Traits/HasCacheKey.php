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
                ...Arr::flatten(array_keys($this->fields)),
                ...Arr::flatten($this->fields),
                ...$this->dates
            ],
            '_'
        );
    }
}
