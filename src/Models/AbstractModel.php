<?php

namespace Dainsys\RingCentral\Models;

use Illuminate\Database\Eloquent\Model;

class AbstractModel extends Model
{
    protected $connection = 'ring_central';
}
