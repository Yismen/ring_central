<?php

namespace Dainsys\RingCentral\Exports\Sheets;

use Illuminate\Database\Eloquent\Collection;

interface SheetsContract
{
    public function data(): Collection;

    public function hasNewData(): bool;

    public function totalKeyField(): string;
}
