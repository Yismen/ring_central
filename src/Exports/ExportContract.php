<?php

namespace Dainsys\RingCentral\Exports;

interface ExportContract
{
    public function hasNewData(): bool;
}
