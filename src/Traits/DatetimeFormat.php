<?php

namespace Lson\Authorization\Traits;

use DateTimeInterface;

trait DatetimeFormat
{
    /**
     * Compatible datetime format
     *
     * @param DateTimeInterface $date
     * @return string
     *
     * @author lsrong
     * @datetime 02/07/2020 20:00
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        if (version_compare(app()->version(), '7.0.0') < 0) {
            return parent::serializeDate($date);
        }

        return $date->format('Y-m-d H:i:s');
    }
}
