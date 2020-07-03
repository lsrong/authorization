<?php


namespace Lson\Authorization\Database;

use Lson\Authorization\Traits\DatetimeFormat;
use Illuminate\Database\Eloquent\Model;

abstract class Base extends Model
{
    use DatetimeFormat;
}