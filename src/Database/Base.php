<?php


namespace Lson\Authorization\Database;

use Illuminate\Database\Eloquent\SoftDeletes;
use Lson\Authorization\Traits\DatetimeFormat;
use Illuminate\Database\Eloquent\Model;

abstract class Base extends Model
{
    use SoftDeletes, DatetimeFormat;
}