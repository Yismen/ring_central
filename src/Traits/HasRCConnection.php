<?php

namespace Dainsys\RingCentral\Traits;

trait HasRCConnection
{
    /**
     * Get the migration connection name.
     *
     * @return string|null
     */
    public function getConnectionName()
    {
        return config('ring_central.connection.name');
    }
}
