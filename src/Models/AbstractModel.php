<?php

namespace Dainsys\RingCentral\Models;

use Illuminate\Database\Eloquent\Model;
use Dainsys\RingCentral\Traits\HasRCConnectionName;

class AbstractModel extends Model
{
    use HasRCConnectionName;
}
