<?php

namespace Dainsys\RingCentral\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Illuminate\Database\Eloquent\Collection;

abstract class AbstractSheet implements FromView, WithTitle, WithEvents
{
    public Collection $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }
}
