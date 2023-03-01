<?php

namespace Dainsys\RingCentral\Models;

use Illuminate\Database\Eloquent\Model;
use Dainsys\RingCentral\Traits\HasRCConnection;

class AbstractModel extends Model
{
    use HasRCConnection;
}
