<?php

namespace Dainsys\RingCentral\Models;

class Hour extends AbstractModel
{
    public function getTable(): string
    {
        return 'vw_ecco_agent_session_raw_summary';
    }
}
