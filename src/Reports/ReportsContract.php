<?php

namespace Dainsys\RingCentral\Reports;

use Illuminate\Support\Collection;

interface ReportsContract
{
    public function handle(array $dates, array $fields, bool $group_by_date = false);

    public function hasNewData(): bool;

    public function hours(): Collection;

    public function calls(): Collection;
}
