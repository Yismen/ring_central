<?php

namespace Dainsys\RingCentral\Traits;

trait HasRCConnectionName
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
