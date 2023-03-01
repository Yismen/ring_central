<?php

namespace Dainsys\RingCentral;

use Illuminate\Support\Arr;

class RingCentral
{
    public static function setConnection(string $name = 'ring_central')
    {
        config()->set("database.connections.{$name}", Arr::except(config('ring_central.connection'), 'name'));
    }
}
