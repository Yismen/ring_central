<?php

namespace Dainsys\RingCentral\Exports\Sheets;

use Illuminate\Database\Eloquent\Collection;

abstract class AbstractSheet
{
    public array $fields;
    public array $dates;
    public Collection $data;

    public function __construct(array $fields, array $dates)
    {
        $this->fields = $fields;
        $this->dates = $dates;

        $this->data = $this->data();
    }

    abstract public function data(): Collection;
}
