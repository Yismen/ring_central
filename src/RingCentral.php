<?php

namespace Dainsys\RingCentral;

class RingCentral
{
    public static function setConnection(string $name = 'ring_central.connection')
    {
        config()->set('database.connections.ring_central', config($name));
    }
}
