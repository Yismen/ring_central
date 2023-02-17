<?php

namespace Dainsys\RingCentral\Models;

class Call extends AbstractModel
{
    public function getTable(): string
    {
        return 'vw_ecco_combined_dialer_result_summary';
    }
}
