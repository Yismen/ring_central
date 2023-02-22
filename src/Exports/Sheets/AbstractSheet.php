<?php

namespace Dainsys\RingCentral\Exports\Sheets;

use Illuminate\Database\Eloquent\Collection;

abstract class AbstractSheet
{
    public array $dial_groups;
    public array $teams;
    public array $dates;
    public Collection $data;

    public function __construct(array $dial_groups, array $teams, array $dates)
    {
        $this->dial_groups = $dial_groups;
        $this->teams = $teams;
        $this->dates = $dates;

        $this->data = $this->data();
    }

    abstract public function data(): Collection;
}
